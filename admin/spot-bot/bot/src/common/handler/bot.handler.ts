import { IBotPayload } from '../interface/bot-payload.interface';
import { CoreService } from 'src/core/core.service';
import AsyncLock from 'async-lock';
import { CurrencyPriceService } from 'src/currency-price/currency-price.service';
import { EventService } from 'src/event/event.service';
import Decimal from 'decimal.js';
import { BotStatusEnum } from '../enum/bot-status.enum';
import { BotTypeEnum } from '../enum/bot-type.enum';
import { getRandomNumberWithDecimal } from 'src/util/random-number.util';
import { spliter } from '../constant/spliter.constant';
import { IOrder } from '../interface/order.interface';
import { randomUUID } from 'crypto';
import { COUNT } from '../constant/count.constant';
import { handleDecimal } from 'src/util/handle-decimal.util';
import { Logger } from '@nestjs/common';
import { set } from 'mongoose';

export class BotHandler {
  private intervalId: NodeJS.Timeout | null = null;
  private readonly logger = new Logger(BotHandler.name);


  private mainCurrencySymbol: string;
  private baseCurrencySymbol: string;

  private buyOrders: IOrder[] = [];
  private sellOrders: IOrder[] = [];

    // private readonly DONE_DISPLAY_LIMIT = 10;
    /**
   * تاریخچه معاملات انجام‌شده.
   * این آرایه بین سیکل‌های اجرای همین BotHandler باقی می‌ماند.
   */
  private doneOrders: IOrder[] = [];

  /**
   * تعداد معاملاتی که در خروجی نمایش داده می‌شوند.
   * چون COUNT در حال حاضر 50 است، حداقل 50 معامله نگه می‌داریم.
   */
  private readonly DONE_DISPLAY_LIMIT = COUNT;

  constructor(
    private readonly marketPair: string,
    private readonly lock: AsyncLock,
    private readonly coreService: CoreService,
    private readonly currencyPriceService: CurrencyPriceService,
    private readonly eventService: EventService,
  ) {
    const parts = this.marketPair.split(spliter);
    this.mainCurrencySymbol = (parts[0] || '').toUpperCase().trim();
    this.baseCurrencySymbol = (parts[1] || 'USDT').toUpperCase().trim();
  }

  async init() {
    await this.initProperties();
  }

  async destroy() {
    this.cancelJob();
    this.reset();
    await this.coreService.emitBotOrder(this.marketPair, { buyers: [], sellers: [], done: [] });
  }

  private async initProperties() {
    const setting = await this.coreService.getSetting(this.mainCurrencySymbol, this.baseCurrencySymbol, BotTypeEnum.SPOT);
    console.log("initProperties", setting)
    if (!setting || setting.type !== BotTypeEnum.SPOT) return;

    this.logger.log(`Bot ${this.marketPair} initialized`);
    await this.handleBot(setting);
  }

  async handleBot(payload: IBotPayload) {
    this.cancelJob();

    if (payload.status === BotStatusEnum.INACTIVE) {
      this.reset();
      await this.coreService.emitBotOrder(this.marketPair, { buyers: [], sellers: [], done: [] });
      return;
    }

    const intervalTime = (payload.secount || 5) * 1000;

    const loop = async () => {
      if (!this.intervalId) return;

      try {
        const rawPrice = await this.currencyPriceService.getPriceOf(
          this.mainCurrencySymbol,
          this.baseCurrencySymbol
        );
        if (rawPrice == null) {
          this.logger.error(`Price service returned null for ${this.marketPair}`);
          return;
        }

        const marketPrice = Number(rawPrice);

        if (!marketPrice || isNaN(marketPrice)) {
          this.logger.error(`Invalid price: ${rawPrice}`);
          return;
        }



        await this.lock.acquire(this.marketPair, async () => {
          await this.simulateTradingCycle(payload, marketPrice);
        });

      } catch (e) {
        this.logger.error(`Cycle error ${this.marketPair}`, e);
      } finally {
        this.intervalId = setTimeout(loop, intervalTime) as any;
      }
    };

    this.intervalId = setTimeout(loop, 0) as any;
  }

  private async simulateTradingCycle(payload: IBotPayload, marketPrice: number) {

                // تبدیل مقادیر از USDT به ارز پایه (مثلاً BTC)
    let leastAmount = payload.leastAmount / marketPrice;
    let mostAmount = payload.mostAmount / marketPrice;
    if(this.baseCurrencySymbol == "IRT"){
      const usdtIrtPrice = await this.currencyPriceService.getUsdtIrtPrice()
      leastAmount = (payload.leastAmount * usdtIrtPrice) / marketPrice;
      mostAmount = (payload.mostAmount * usdtIrtPrice) / marketPrice;
     
    }
 
    const buyPercent = Math.max(payload.buyPercent ?? 0.01, 0.0001);
    const sellPercent = Math.max(payload.sellPercent ?? 0.01, 0.0001);

    const LEVELS = COUNT || 10;

    const buys = this.generateLevels(true, marketPrice, buyPercent, LEVELS, payload, leastAmount, mostAmount);
    const sells = this.generateLevels(false, marketPrice, sellPercent, LEVELS, payload, leastAmount, mostAmount);

    // 🎯 تنظیم best bid/ask
    const spread = buyPercent / 100;

    buys[0].price = this.normalizePrice(marketPrice * (1 - spread / 2));
    sells[0].price = this.normalizePrice(marketPrice * (1 + spread / 2));

    /**
     * نگه‌داشتن آخرین وضعیت order book در حافظه.
     * در صورت نیاز بعداً می‌توان از این داده برای حرکت نرم‌تر سفارش‌ها هم استفاده کرد.
     */
    this.buyOrders = buys;
    this.sellOrders = sells;

    /**
     * تولید معاملات انجام‌شده از روی buy/sell فعلی
     * و ترکیب آن با معاملات سیکل‌های قبلی.
     */
    const done = this.generateDoneOrdersFromOrderBook(
      buys,
      sells,
      payload,
      leastAmount,
      mostAmount,
      marketPrice,
    );

    await this.coreService.emitBotOrder(this.marketPair, {
      buyers: buys,
      sellers: sells,
      done,
    });

 
  }

  private generateLevels(
    isBuy: boolean,
    marketPrice: number,
    percent: number,
    count: number,
    payload: IBotPayload,
    leastAmount: number,
     mostAmount: number,
  ): IOrder[] {

    const orders: IOrder[] = [];
    for (let i = 0; i < count; i++) {

      const base = percent / 100;

      // 🎯 scale based on price magnitude
      const magnitude = Math.max(1, -Math.floor(Math.log10(marketPrice)));
      const scale = Math.min(magnitude, 6);

      const distance =
        i === 0
          ? base * 0.15
          : base * (1 + Math.pow(i, 0.85) * 0.12 * scale);

      let price = this.normalizePrice(
        marketPrice * (isBuy ? (1 - distance) : (1 + distance))
      );
        // 🔥 FIX: جلوگیری از duplicate collapse
        if (i > 0) {
          const prev = orders[i - 1]?.price;
          if (prev && price === prev) {
            price += this.getTickSize(price) * (i + 1);
          }
        }
      const decimalPlaces = this.getDecimalPlacesForAmount(mostAmount ?? leastAmount ?? 100);

      const amount = getRandomNumberWithDecimal(
        // payload.leastAmount ?? 100,
        // payload.mostAmount ?? 1000,
        leastAmount ?? 100,
        mostAmount ?? 1000,
        decimalPlaces
      );

      orders.push(this.createOrder(marketPrice, price, amount, isBuy ? 1 : 2));
    }

    return isBuy
      ? orders.sort((a, b) => b.price - a.price)
      : orders.sort((a, b) => a.price - b.price);
  }
  private generateDoneOrdersFromOrderBook(
    buys: IOrder[],
    sells: IOrder[],
    payload: IBotPayload,
    leastAmount: number,
    mostAmount: number,
    marketPrice: number,
  ): IOrder[] {
    // مقدار هدف (مثلاً 50)
    const targetCount = COUNT || 50;

    // اگر کلاً سفارشی برای معامله وجود ندارد، همان دیتای قبلی را برگردان
    if (!buys.length || !sells.length) {
      return this.doneOrders.slice(0, targetCount);
    }

    const newDoneOrders: IOrder[] = [];

    /**
     * 1. تعیین تعداد معاملات جدید در این سیکل
     * اگر لیست خالی باشد، کل 50 تا را می‌سازیم.
     * اگر لیست پر باشد، بین 3 تا 8 معامله جدید برای "زنده" نشان دادن مارکت اضافه می‌کنیم.
     */
    let tradesToGenerate = 0;
    if (this.doneOrders.length < targetCount) {
      tradesToGenerate = targetCount - this.doneOrders.length;
    } else {
      // در هر آپدیت چندتای جدید اضافه کن (مثلاً بین 3 تا 8 عدد)
      tradesToGenerate = this.getRandomInteger(3, 8);
    }

    // حلقه را تا زمانی ادامه بده که به تعداد مورد نظر برسیم (جلوگیری از null ماندن)
    let attempts = 0;
    while (newDoneOrders.length < tradesToGenerate && attempts < tradesToGenerate * 2) {
      const prev = newDoneOrders[newDoneOrders.length - 1] ?? this.doneOrders[0];
      const trade = this.createDoneOrderFromOrderBook(
        buys,
        sells,
        payload,
        leastAmount,
        mostAmount,
        marketPrice,
        prev,
        newDoneOrders.length
      );

      if (trade) {
        newDoneOrders.push(trade);
      }
      attempts++;
    }

    /**
     * 2. ترکیب و نوسازی لیست
     * جدیدها را به ابتدای لیست قبلی می‌چسبانیم.
     */
    this.doneOrders = [...newDoneOrders, ...this.doneOrders];

    /**
     * 3. مرتب‌سازی بر اساس زمان و برش لیست
     * همیشه دقیقاً targetCount عدد برمی‌گردانیم.
     */
    this.doneOrders = this.doneOrders
      .sort((a, b) => b.timestamp - a.timestamp)
      .slice(0, targetCount);

    // اگر باز هم به هر دلیلی کم بود (مثلاً در اجرای اول)، 
    // با معاملات فیک پرش کن تا همیشه COUNT رعایت شود.
    if (this.doneOrders.length < targetCount) {
      this.fillDoneOrdersToLimit(buys, sells, targetCount, marketPrice);
    }

    return [...this.doneOrders];
  }

  private fillDoneOrdersToLimit(buys: IOrder[], sells: IOrder[], target: number, marketPrice: number) {
    const needed = target - this.doneOrders.length;
    for (let i = 0; i < needed; i++) {
      const isBuy = Math.random() > 0.5;
      const source = isBuy ? sells[0] : buys[0];
      const price = source?.price || marketPrice;
      
      this.doneOrders.push({
        _id: randomUUID(),
        amount: source?.amount || 1,
        price: price,
        side: isBuy ? 1 : 2,
        total: (source?.amount || 1) * price,
        filled_amount: source?.amount || 1,
        remainingAmount: 0,
        marketPrice: marketPrice.toString(),
        pair: this.marketPair,
        timestamp: Date.now() - (i + 1000), // ایجاد فاصله زمانی قدیمی‌تر
        type: 7,
      });
    }
  }



  private getRandomInteger(min: number, max: number): number {
    const safeMin = Math.ceil(Math.min(min, max));
    const safeMax = Math.floor(Math.max(min, max));

    if (safeMax < safeMin) {
      return safeMin;
    }

    return Math.floor(Math.random() * (safeMax - safeMin + 1)) + safeMin;
  }


  private createDoneOrderFromOrderBook(
    buys: IOrder[],
    sells: IOrder[],
    payload: IBotPayload,
    leastAmount: number,
    mostAmount: number,
    marketPrice: number,
    previousTrade?: IOrder,
    index: number = 0,
  ): IOrder | null {
    if (!buys.length || !sells.length) {
      return null;
    }

    /*
    * side = 1:
    * خریدار سفارش فروش موجود در asks را برداشته است؛
    * بنابراین معامله از روی sells ساخته می‌شود.
    *
    * side = 2:
    * فروشنده سفارش خرید موجود در bids را برداشته است؛
    * بنابراین معامله از روی buys ساخته می‌شود.
    *
    * با احتمال 60 درصد جهت معامله قبلی ادامه پیدا می‌کند تا
    * streakهای خرید و فروش طبیعی‌تر ایجاد شوند.
    */
    let isBuyTrade: boolean;

    if (previousTrade && Math.random() < 0.6) {
      isBuyTrade = previousTrade.side === 1;
    } else {
      isBuyTrade = Math.random() < 0.5;
    }

    const sourceOrders = isBuyTrade ? sells : buys;

    /*
    * بیشتر معاملات از سطوح ابتدایی order book انتخاب می‌شوند.
    * توان 2 باعث می‌شود اعداد کوچک‌تر شانس بیشتری داشته باشند.
    *
    * مثلاً اگر 50 سفارش داشته باشیم، اغلب یکی از 10 سفارش اول
    * انتخاب می‌شود؛ اما امکان انتخاب سطوح عمیق‌تر نیز وجود دارد.
    */
    const randomLevel = Math.floor(
      Math.pow(Math.random(), 2) * sourceOrders.length,
    );

    const levelIndex = Math.min(
      randomLevel,
      sourceOrders.length - 1,
    );

    const sourceOrder = sourceOrders[levelIndex];

    if (
      !sourceOrder ||
      !Number.isFinite(sourceOrder.price) ||
      sourceOrder.price <= 0
    ) {
      return null;
    }

    /*
    * قیمت پایه معامله دقیقاً از سفارش انتخاب‌شده گرفته می‌شود.
    */
    let price = sourceOrder.price;

    /*
    * donePercent فقط برای ایجاد نوسان بسیار جزئی روی قیمت معامله است.
    * برای اینکه معامله خیلی از order book فاصله نگیرد، مقدار نوسان
    * حداکثر به چند tick محدود می‌شود.
    */
    const donePercent =
      Math.max(payload.donePercent ?? 0.01, 0.0001) / 100;

    const tick = this.getTickSize(price);

    const percentVariation = new Decimal(price)
      .mul(donePercent)
      .toNumber();

    /*
    * نوسان قیمت نباید آن‌قدر زیاد باشد که معامله از سفارش مرجع
    * کاملاً جدا شود.
    */
    const maxJitter = Math.max(
      tick,
      Math.min(percentVariation, tick * 5),
    );

    const jitter = (Math.random() - 0.5) * 2 * maxJitter;

    price = this.normalizePrice(price + jitter);

    /*
    * اگر به دلیل قیمت بسیار کوچک یا محاسبات اعشاری قیمت نامعتبر شد،
    * همان قیمت سفارش اصلی استفاده می‌شود.
    */
    if (!Number.isFinite(price) || price <= 0) {
      price = this.normalizePrice(sourceOrder.price);
    }

    /*
    * محدوده مقدار سفارش را استاندارد می‌کنیم تا اگر least و most
    * برعکس ارسال شده بودند نیز مشکلی ایجاد نشود.
    */
    const safeLeastAmount =
      Number.isFinite(leastAmount) && leastAmount > 0
        ? leastAmount
        : 0;

    const safeMostAmount =
      Number.isFinite(mostAmount) && mostAmount > 0
        ? mostAmount
        : safeLeastAmount;

    const minimumAmount = Math.min(
      safeLeastAmount,
      safeMostAmount,
    );

    const maximumAmount = Math.max(
      safeLeastAmount,
      safeMostAmount,
    );

    /*
    * مقدار قابل استفاده از سفارش مرجع:
    *
    * اول remainingAmount بررسی می‌شود.
    * اگر معتبر نبود amount اصلی سفارش استفاده می‌شود.
    */
    const sourceRemainingAmount = Number(
      sourceOrder.remainingAmount,
    );

    const sourceOriginalAmount = Number(sourceOrder.amount);

    const sourceAvailableAmount =
      Number.isFinite(sourceRemainingAmount) &&
      sourceRemainingAmount > 0
        ? sourceRemainingAmount
        : sourceOriginalAmount;

    /*
    * بخشی از سفارش مرجع معامله می‌شود.
    * معمولاً بین 25 تا 100 درصد مقدار قابل‌دسترس سفارش.
    */
    const executionRatio =
      getRandomNumberWithDecimal(0.25, 1, 4);

    let amount = new Decimal(sourceAvailableAmount)
      .mul(executionRatio)
      .toNumber();

    /*
    * amount باید در محدوده تنظیمات ربات باقی بماند؛
    * البته هیچ‌وقت نباید بیشتر از مقدار سفارش مرجع باشد.
    */
    if (minimumAmount > 0) {
      amount = Math.max(amount, minimumAmount);
    }

    if (maximumAmount > 0) {
      amount = Math.min(amount, maximumAmount);
    }

    amount = Math.min(amount, sourceAvailableAmount);

    /*
    * اگر مقدار سفارش مرجع به هر دلیل نامعتبر بود،
    * amount جدید از محدوده تنظیمات ربات ساخته می‌شود.
    */
    if (!Number.isFinite(amount) || amount <= 0) {
      if (maximumAmount <= 0) {
        return null;
      }

      const decimalPlaces =
        this.getDecimalPlacesForAmount(maximumAmount);

      amount = getRandomNumberWithDecimal(
        minimumAmount || maximumAmount,
        maximumAmount,
        decimalPlaces,
      );
    }

    /*
    * تعداد رقم اعشار amount بر اساس بزرگی آن مشخص می‌شود.
    */
    const amountDecimalPlaces =
      this.getDecimalPlacesForAmount(
        maximumAmount || amount,
      );

    amount = Number(
      new Decimal(amount)
        .toDecimalPlaces(
          amountDecimalPlaces,
          Decimal.ROUND_DOWN,
        )
        .toString(),
    );

    /*
    * در صورت صفر شدن amount بعد از گرد کردن، کمترین مقدار قابل نمایش
    * را ایجاد می‌کنیم؛ البته از مقدار سفارش مرجع بیشتر نمی‌شود.
    */
    if (!Number.isFinite(amount) || amount <= 0) {
      const minimumStep = new Decimal(10)
        .pow(-amountDecimalPlaces)
        .toNumber();

      amount = Math.min(
        sourceAvailableAmount,
        Math.max(minimumStep, minimumAmount),
      );
    }

    amount = +handleDecimal(amount.toString());

    if (!Number.isFinite(amount) || amount <= 0) {
      return null;
    }

    /*
    * total با Decimal محاسبه می‌شود تا خطای floating-point جاوااسکریپت
    * برای قیمت‌ها و amountهای کوچک کاهش پیدا کند.
    */
    const total = new Decimal(amount)
      .mul(price)
      .toNumber();

    const normalizedTotal = +handleDecimal(total.toString());

    /*
    * قیمت بازار واقعی‌تر برای ثبت روی معامله:
    * میانگین بهترین bid و ask.
    */
    const bestBid = buys[0]?.price;
    const bestAsk = sells[0]?.price;

    const calculatedMarketPrice =
      Number.isFinite(bestBid) &&
      bestBid > 0 &&
      Number.isFinite(bestAsk) &&
      bestAsk > 0
        ? new Decimal(bestBid)
            .plus(bestAsk)
            .div(2)
            .toNumber()
        : marketPrice;

    /*
    * برای اینکه معاملات تولیدشده در یک سیکل timestamp یکسان نداشته
    * باشند، بر اساس index چند میلی‌ثانیه فاصله ایجاد می‌کنیم.
    *
    * index صفر جدیدترین معامله خواهد بود.
    */
    const timestamp = Date.now() - index;

    return {
      _id: randomUUID(),

      amount,

      price,

      /*
      * side = 1: معامله خرید روی ask
      * side = 2: معامله فروش روی bid
      */
      side: isBuyTrade ? 1 : 2,

      total: normalizedTotal,

      /*
      * چون این رکورد در بخش معاملات انجام‌شده قرار دارد،
      * تمام amount پر شده و remainingAmount صفر است.
      */
      filled_amount: amount,
      remainingAmount: 0,

      marketPrice: calculatedMarketPrice.toString(),

      pair: this.marketPair,

      timestamp,

      type: 7,
    };
  }



// private generateDoneOrders(bestBid: number, bestAsk: number, payload: IBotPayload,  leastAmount: number,
//      mostAmount: number): IOrder[] {
//   const list: IOrder[] = [];

//   const donePercent = Math.max(payload.donePercent ?? 0.01, 0.0001) / 100;

//   for (let i = 0; i < this.DONE_DISPLAY_LIMIT; i++) {

//     // 🎯 random side (buy/sell)
//     const isBuy = Math.random() < 0.5;

//     // 🎯 base price نزدیک best bid/ask
//     const base = isBuy ? bestBid : bestAsk;

//     // 🎯 نوسان کنترل‌شده با donePercent
//     const variation = base * donePercent;

//     let price = isBuy
//       ? base - Math.random() * variation   // BUY زیر bid
//       : base + Math.random() * variation;  // SELL بالای ask

//     // 🔥 micro jitter برای طبیعی‌تر شدن
//     const tick = this.getTickSize(price);
//     price += (Math.random() - 0.5) * tick * 5;

//     price = this.normalizePrice(price);
//     const decimalPlaces = this.getDecimalPlacesForAmount(mostAmount ?? leastAmount ?? 100);

//     const amount = getRandomNumberWithDecimal(
//       // payload.leastAmount ?? 100,
//       // payload.mostAmount ?? 1000,
//       leastAmount ?? 100,
//       mostAmount ?? 1000,
//       decimalPlaces
//     );

//     list.push({
//       _id: randomUUID(),
//       amount,
//       price,
//       side: isBuy ? 1 : 2,
//       total: amount * price,
//       filled_amount: amount,
//       remainingAmount: 0,
//       marketPrice: ((bestBid + bestAsk) / 2).toString(),
//       pair: this.marketPair,
//       timestamp: Date.now(),
//       type: 7,
//     });
//   }

//   return list;
// }

private getTickSize(price: number): number {
  if (price >= 1000) return 0.01;
  if (price >= 1) return 0.0001;
  if (price >= 0.01) return 0.000001;

  // 🔥 FIX: micro-coins (SHIB etc)
  if (price >= 0.000001) return 0.000000001;

  return 0.0000000001;
}
private normalizePrice(price: number): number {
  if (!price || price <= 0) return 0;

  const tick = this.getTickSize(price);

  const p = Math.round(price / tick) * tick;

  return Number(new Decimal(p).toSignificantDigits(12).toNumber());
}


  private createOrder(marketPrice: number, price: number, amount: number, side: number): IOrder {
    const normalizedPrice = this.normalizePrice(price);
    const total = Decimal.mul(amount, normalizedPrice).toNumber();
    const fillRatio = getRandomNumberWithDecimal(0.1, 1.0, 2);
    let filledAmount = amount * fillRatio;
    filledAmount = +handleDecimal(filledAmount.toString());
    const remainingAmount = +handleDecimal((amount - filledAmount).toString());

    return {
      _id: randomUUID(),
      amount: +handleDecimal(amount.toString()),
      price: normalizedPrice,
      side,
      total: +handleDecimal(total.toString()),
      filled_amount: filledAmount,
      remainingAmount,
      marketPrice: marketPrice.toString(),
      pair: this.marketPair,
      timestamp: Date.now(),
      type: 7,
    };
  }

  private cancelJob() {
    if (this.intervalId) {
      clearTimeout(this.intervalId);
      this.intervalId = null;
    }
  }

  private reset() {
    this.buyOrders = [];
    this.sellOrders = [];
  }

  private getDecimalPlacesForAmount(value: number): number {
  if (value < 0.000001) return 12;
  if (value < 0.0001) return 10;
  if (value < 0.01) return 8;
  if (value < 1) return 6;
  return 4;
}
}