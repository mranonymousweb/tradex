import Decimal from 'decimal.js';
import { Kafka } from 'kafkajs';
import { IOrderPayload } from 'src/common/interface/order-payload.interface';
import { OrderSideEnum, OrderStatusEnum, OrderTypeEnum } from 'src/common/enum/order.enum';
import { RedisService } from 'src/redis/redis.service';
import { FeeCalculationService } from './fee-calculation.service';
import { OrderActivationService } from './order-activation.service';
import { OrderTakerBotService } from 'src/order-taker-bot/order-taker-bot.service';
import { OrderTakerBot } from 'src/order-taker-bot/entities/order-taker-bot.entity';

/**
 * دسترسی‌هایی که Runner برای کار روی یک MarketEngine لازم دارد.
 * چون Runner جدا از Engine است، مستقیم به فیلدهای private دسترسی ندارد
 * و از طریق همین host با Engine حرف می‌زند.
 */
export interface MarketOrderTakerBotHost {
  /** جفت ارز فعلی engine مثل BTC_USDT */
  getPair(): string;

  /**
   * آیا الان مجاز به اجرای taker هستیم؟
   * باید true باشد وقتی:
   * - processing = false (مچینگ سفارش جدید در جریان نیست)
   * - cancelling = false (کنسل در جریان نیست)
   */
  canRunTaker(): boolean;

  getBuyOrders(): IOrderPayload[];
  setBuyOrders(orders: IOrderPayload[]): void;
  getSellOrders(): IOrderPayload[];
  setSellOrders(orders: IOrderPayload[]): void;
  getLastTradePrice(): Decimal;
  publishOrderBook(): Promise<void>;
  publishUserOrders(order: IOrderPayload): Promise<void>;
}

/** وابستگی‌های سرویس که از Nest به Runner پاس داده می‌شوند */
export interface MarketOrderTakerBotDeps {
  orderTakerBotService: OrderTakerBotService;
  redisService: RedisService;
  feeCalculationService: FeeCalculationService;
  orderActivationService: OrderActivationService;
  kafka: Kafka;
}

/**
 * اجرای خودکار Order Taker Bot برای یک مارکت.
 *
 * مهم: این کلاس Nest Injectable نیست.
 * هر MarketEngine باید با `new MarketOrderTakerBotRunner(deps)` نمونهٔ خودش را بسازد.
 * اگر singleton باشد، attach() آخرین pair، host بقیه را overwrite می‌کند
 * و waiting یک pair می‌تواند پردازش pair دیگر را قفل کند.
 *
 * جریان کلی:
 * 1) هر ۱ دقیقه کانفیگ bot را از DB/کش می‌گیرد (symbol = پایه بازار)
 * 2) هر secount ثانیه takenOrder را اجرا می‌کند (اگر idle باشد)
 */
export class MarketOrderTakerBotRunner {
  /** اتصال به MarketEngine فعلی */
  private host: MarketOrderTakerBotHost | null = null;

  /** کانفیگ فعلی bot (null یعنی برای این pair فعال نیست) */
  private orderTakerBot: OrderTakerBot | null = null;

  /** تایمر اجرای takenOrder بر اساس secount */
  private takerBotInterval: NodeJS.Timeout | null = null;

  /** تایمر رفرش کانفیگ bot هر ۶۰ ثانیه */
  private takerBotRefreshInterval: NodeJS.Timeout | null = null;

  /** true یعنی الان takenOrder در حال اجراست */
  private takerBotRunning = false;

  /** آخرین secount که با آن interval ساخته شده (برای تشخیص تغییر) */
  private lastTakerBotSecount: number | null = null;

  private producer;

  constructor(private readonly deps: MarketOrderTakerBotDeps) {
    this.producer = this.deps.kafka.producer();
    this.producer.connect().then(() => {
      console.log('Kafka producer connected in MarketOrderTakerBotRunner!');
    });
  }

  /**
   * اتصال Runner به یک MarketEngine.
   * باید قبل از start() صدا زده شود.
   */
  attach(host: MarketOrderTakerBotHost) {
    this.host = host;
  }

  /**
   * روشن کردن لوپ‌های bot:
   * - یک‌بار کانفیگ را می‌گیرد
   * - هر ۱ دقیقه کانفیگ را رفرش می‌کند
   * - هر secount ثانیه takenOrder را اجرا می‌کند
   */
  async start() {
    if (!this.host) {
      return;
    }

    await this.refreshOrderTakerBot();
    await this.startOrderTakerBotRefreshLoop();
    await this.startOrderTakerBotExecutionLoop();
  }

  /**
   * خاموش کردن کامل Runner (موقع destroy شدن engine)
   */
  stop() {
    this.clearOrderTakerBotLoops();
    this.orderTakerBot = null;
    this.host = null;
  }

  /** آیا takenOrder الان در حال اجراست؟ */
  isRunning(): boolean {
    return this.takerBotRunning;
  }

  /**
   * صبر می‌کند تا takenOrder تمام شود.
   * در processOrder قبل از NEW/CANCEL صدا زده می‌شود
   * تا وسط کار بات، سفارش جدید یا کنسل قاطی نشود.
   */
  async waitUntilIdle() {
    while (this.takerBotRunning) {
      await new Promise((resolve) => setTimeout(resolve, 10));
    }
  }

  private getPair(): string {
    return this.host!.getPair();
  }

  /**
   * پایه بازار از pair.
   * مثال: BTC_USDT → USDT
   */
  private getBaseSymbol(): string | null {
    const pair = this.getPair();
    if (!pair) return null;
    const parts = pair.split('_');
    return parts.length >= 2 ? parts[1] : null;
  }

  /**
   * آیا این pair داخل فیلد currencies بات فعال است؟
   * انتظار: array از { symbol: "BTC" } و مشابه
   */
  private isPairEnabledInCurrencies(currencies: any): boolean {
    if (!currencies || !Array.isArray(currencies)) return false;

    const pair = this.getPair();
    const mainSymbol = pair.split('_')[0];
    return currencies.some((currency) => currency?.symbol === mainSymbol);
  }

  /**
   * خواندن کانفیگ فعال bot برای پایه این مارکت.
   * اگر bot نبود یا pair داخل currencies نبود → bot خاموش می‌شود.
   * اگر secount عوض شده بود → interval اجرا دوباره ساخته می‌شود.
   */
  private async refreshOrderTakerBot() {
    const baseSymbol = this.getBaseSymbol();

    if (!baseSymbol) {
      this.orderTakerBot = null;
      await this.restartOrderTakerBotExecutionLoop();
      return;
    }

    const bot =
      await this.deps.orderTakerBotService.findActiveBySymbol(baseSymbol);

    if (!bot || !this.isPairEnabledInCurrencies(bot.currencies)) {
      this.orderTakerBot = null;
      this.restartOrderTakerBotExecutionLoop();
      return;
    }

    const secountChanged = this.lastTakerBotSecount !== bot.secount;
    this.orderTakerBot = bot;

    if (secountChanged) {
      this.restartOrderTakerBotExecutionLoop();
    }
  }

  /** هر ۶۰ ثانیه کانفیگ bot را از کش/DB دوباره می‌گیرد */
  private startOrderTakerBotRefreshLoop() {
    if (this.takerBotRefreshInterval) return;

    this.takerBotRefreshInterval = setInterval(() => {
      void this.refreshOrderTakerBot();
    }, 60_000);
  }

  /**
   * ساخت interval اجرای takenOrder با فاصله secount ثانیه.
   * اگر bot فعال نباشد یا secount نامعتبر باشد، interval ساخته نمی‌شود.
   */
  private startOrderTakerBotExecutionLoop() {
    this.clearTakerBotInterval();

    if (!this.orderTakerBot?.secount || this.orderTakerBot.secount <= 0) {
      this.lastTakerBotSecount = null;
      return;
    }

    this.lastTakerBotSecount = this.orderTakerBot.secount;

    this.takerBotInterval = setInterval(() => {
      void this.tryRunTakenOrderFromBot();
    }, this.orderTakerBot.secount * 1000);
  }

  /** پاک کردن و ساخت دوباره interval اجرا (بعد از تغییر secount) */
  private restartOrderTakerBotExecutionLoop() {
    this.startOrderTakerBotExecutionLoop();
  }

  private clearTakerBotInterval() {
    if (this.takerBotInterval) {
      clearInterval(this.takerBotInterval);
      this.takerBotInterval = null;
    }
  }

  private clearOrderTakerBotLoops() {
    this.clearTakerBotInterval();
    if (this.takerBotRefreshInterval) {
      clearInterval(this.takerBotRefreshInterval);
      this.takerBotRefreshInterval = null;
    }
  }

  /**
   * تلاش برای اجرای takenOrder.
   * اجرا نمی‌شود اگر:
   * - matching در حال پردازش باشد (processing=true)
   * - کنسل در جریان باشد (cancelling=true)
   * - خود taker الان در حال اجرا باشد
   * - bot برای این pair فعال نباشد
   */
  private async tryRunTakenOrderFromBot() {
    if (!this.host) return;

    if (!this.host.canRunTaker() || this.takerBotRunning) {
      return;
    }

    if (!this.orderTakerBot) {
      return;
    }

    this.takerBotRunning = true;
    try {
      await this.takenOrder(this.orderTakerBot);
    } catch (error) {
      console.error(
        `OrderTakerBot takenOrder error for ${this.getPair()}:`,
        error,
      );
    } finally {
      this.takerBotRunning = false;
    }
  }

  /**
   * منطق اصلی TAKEN:
   * - بازه قیمت مجاز را با buyPercent / sellPercent می‌سازد
   * - سفارش‌های خارج از بازه را از book برمی‌دارد
   * - به Kafka با type=TAKEN می‌فرستد
   * - order book و سفارش کاربر را آپدیت می‌کند
   */
  private async takenOrder(bot: OrderTakerBot) {
    const pair = this.getPair();
   
    const tempPrice = await this.deps.redisService.getPairPrice(pair);
    if (!tempPrice?.last) {
      console.error(`price not found for takenOrder pair=${pair}`);
      return;
    }

    const last = Number(tempPrice.last);
    const marketPriceBuy = last + (Number(bot.buyPercent) / 100) * last;
    const marketPriceSell = last - (Number(bot.sellPercent) / 100) * last;

    const buyOrders = this.host!.getBuyOrders();
    const sellOrders = this.host!.getSellOrders();

    const notValidBuy = buyOrders.filter(
      (item) =>
        Number(item.price) >= marketPriceBuy ||
        item.type === OrderTypeEnum.MARKET,
    );
    this.host!.setBuyOrders(
      buyOrders.filter(
        (item) =>
          Number(item.price) < marketPriceBuy &&
          item.type !== OrderTypeEnum.MARKET,
      ),
    );

    const notValidSell = sellOrders.filter(
      (item) =>
        Number(item.price) <= marketPriceSell ||
        item.type === OrderTypeEnum.MARKET,
    );
    this.host!.setSellOrders(
      sellOrders.filter(
        (item) =>
          Number(item.price) > marketPriceSell &&
          item.type !== OrderTypeEnum.MARKET,
      ),
    );

    // const stopBuys =
    //   await this.deps.orderActivationService.getPendingBuysOrders(pair);
    // const notValidStopBuy = stopBuys.filter(
    //   (item) => Number(item.price) >= marketPriceBuy,
    // );
    // const validStopBuy = stopBuys.filter(
    //   (item) => Number(item.price) < marketPriceBuy,
    // );
    // await this.deps.orderActivationService.setWaitingList(
    //   validStopBuy,
    //   pair,
    //   OrderSideEnum.BUY,
    // );

    // const stopSells =
    //   await this.deps.orderActivationService.getPendingSellsOrders(pair);
    // const notValidStopSell = stopSells.filter(
    //   (item) => Number(item.price) <= marketPriceSell,
    // );
    // const validStopSell = stopSells.filter(
    //   (item) => Number(item.price) > marketPriceSell,
    // );
    // await this.deps.orderActivationService.setWaitingList(
    //   validStopSell,
    //   pair,
    //   OrderSideEnum.SELL,
    // );

    const mergedOrders = [
      ...notValidBuy,
      ...notValidSell,
      // ...notValidStopBuy,
      // ...notValidStopSell,
    ];

    if (!mergedOrders.length) {
      return;
    }
     console.log("takenOrder", pair)

    const lastTradePrice = this.host!.getLastTradePrice();

    for (const order of mergedOrders) {
      const isBuy = order.side === OrderSideEnum.BUY;
      const final = isBuy ? order.amount : order.total;

      console.log(
        `Order ID: ${order.id}, Pair: ${order.pairs}, Price: ${order.price} , BotFee:${final}`,
      );

      const tradeAmount = (new Decimal(order.amount)).minus(new Decimal(order.filled_amount))
      const makerFee = await this.deps.feeCalculationService.calcFee(
        order,
        tradeAmount,
        'maker',
        isBuy,
        lastTradePrice,
      );
      order.maker = {
        id: order.id,
        fee: makerFee.toString(),
        status: OrderStatusEnum.DONE,
        meta: JSON.stringify(order.meta),
      };
      // order.amount = tradeAmount.toString();
      // order.total = tradeAmount.mul(lastTradePrice).toString();
    }

    try {
      await this.producer.send({
        topic: 'spot-trades',
        messages: [
          {
            value: JSON.stringify({
              data: JSON.stringify(mergedOrders),
              type: 'TAKEN',
            }),
          },
        ],
      });
      console.log('Trade sent taken order  Kafka:', mergedOrders);
    } catch (error) {
      console.error('Error sending trade to Kafka:', error);
    }

    await this.host!.publishOrderBook();
    for (const order of mergedOrders) {
      order.status = OrderStatusEnum.DONE;
      await this.host!.publishUserOrders(order);
    }
  }
}
