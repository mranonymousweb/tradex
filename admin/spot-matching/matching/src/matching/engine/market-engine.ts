import { IOrderPayload } from "src/common/interface/order-payload.interface";
import { CoreService } from "src/core/core.service";
import { Inject, Injectable } from "@nestjs/common";
import { OrderValidationService } from "./services/order-validation.service";
import { OrderActivationService } from "./services/order-activation.service";
import { MatchingService } from "./services/matching.service";
import { FeeCalculationService } from "./services/fee-calculation.service";
import Decimal from "decimal.js";
import { OrderSideEnum, OrderStatusEnum, OrderTypeEnum } from "src/common/enum/order.enum";
import { Kafka } from "kafkajs";
import { KAFKA_CLIENT } from "src/kafka/constant/kafka-client.constant";
import { RedisService } from "src/redis/redis.service";
import { MarketCurrencyService } from "src/market-currency/market-currency.service";
import { OrderTakerBotService } from "src/order-taker-bot/order-taker-bot.service";
import { MarketOrderTakerBotRunner } from "./services/market-order-taker-bot.runner";

type QueueItem = {
  order: IOrderPayload;
  resolve: () => void;
};

type OrderQueue = IOrderPayload[];

interface PriceLevel {
  price: number;
  orders: OrderQueue;
}

@Injectable()
export class MarketEngine {
  private pair: string;
  private producer;

  private buyOrders: IOrderPayload[] = [];
  private sellOrders: IOrderPayload[] = [];
  private trades: any[] = [];
  private queue: QueueItem[] = [];
  /** true = الان سفارش جدید در حال مچینگ است */
  private processing = false;
  /** true = الان کنسل در حال اجراست */
  private cancelling = false;
  private lastTradePrice: Decimal = new Decimal(0);

  /** یک Runner جدا برای همین engine — نه singleton مشترک بین pairها */
  private readonly orderTakerBotRunner: MarketOrderTakerBotRunner;

  constructor(
    private readonly coreService: CoreService,
    private readonly orderValidationService: OrderValidationService,
    private readonly orderActivationService: OrderActivationService,
    private readonly matchingService: MatchingService,
    private readonly feeCalculationService: FeeCalculationService,
    private readonly marketCurrencyService: MarketCurrencyService,
    @Inject(KAFKA_CLIENT) private readonly kafka: Kafka,
    private readonly redisService: RedisService,
    private readonly orderTakerBotService: OrderTakerBotService,
  ) {
    this.producer = this.kafka.producer();
    this.producer.connect().then(() => {
      console.log("Kafka producer connected in constructor!");
    });

    // ساخت دستی تا هر MarketEngine نمونهٔ مستقل داشته باشد
    this.orderTakerBotRunner = new MarketOrderTakerBotRunner({
      orderTakerBotService: this.orderTakerBotService,
      redisService: this.redisService,
      feeCalculationService: this.feeCalculationService,
      orderActivationService: this.orderActivationService,
      kafka: this.kafka,
    });
  }

  configure(pair: string) {
    this.pair = pair;
  }

  async onModuleInit() {}

  /**
   * اتصال و روشن کردن Order Taker Bot برای این مارکت.
   *
   * host = دسترسی‌هایی که Runner از Engine لازم دارد
   * canRunTaker: فقط وقتی processing و cancelling هر دو false باشند
   */
  async init() {
    this.orderTakerBotRunner.attach({
      getPair: () => this.pair,
      // taker فقط وقتی مچینگ و کنسل بیکار باشند اجرا شود
      canRunTaker: () => !this.processing && !this.cancelling,
      getBuyOrders: () => this.buyOrders,
      setBuyOrders: (orders) => {
        this.buyOrders = orders;
      },
      getSellOrders: () => this.sellOrders,
      setSellOrders: (orders) => {
        this.sellOrders = orders;
      },
      getLastTradePrice: () => this.lastTradePrice,
      publishOrderBook: () => this.publishOrderBook(),
      publishUserOrders: (order) => this.publishUserOrders(order),
    });

    await this.orderTakerBotRunner.start();
  }

  getLastTradePrice(): Decimal | null {
    return this.lastTradePrice;
  }

  async destroy() {
    this.orderTakerBotRunner.stop();
    this.buyOrders = [];
    this.sellOrders = [];
    this.trades = [];
    this.queue = [];
  }

  async processOrder(order: IOrderPayload | any, order_type = "NEW") {
    // اگر taker در حال اجراست، صبر کن تا تمام شود؛ بعد NEW/CANCEL ادامه یابد
    await this.orderTakerBotRunner.waitUntilIdle();

    const tempPrice = await this.redisService.getPairPrice(order.pairs);
    this.lastTradePrice = new Decimal(tempPrice.last);

    this.buyOrders = this.buyOrders.filter(
      (item) => item.status !== OrderStatusEnum.DONE,
    );
    this.sellOrders = this.sellOrders.filter(
      (item) => item.status !== OrderStatusEnum.DONE,
    );

    const marketCurrncy = await this.marketCurrencyService.findByPair(
      this.pair,
      ["id", "pairs", "increasePercent", "decreasePercent"],
    );

    let buyPrice = this.lastTradePrice;
    let sellPrice = this.lastTradePrice;
    if (marketCurrncy) {
      const increaseAmount = this.lastTradePrice.times(
        Number(marketCurrncy.increasePercent) / 100,
      );
      const decreaseAmount = this.lastTradePrice.times(
        Number(marketCurrncy.decreasePercent) / 100,
      );

      buyPrice = this.lastTradePrice.plus(increaseAmount);
      sellPrice = this.lastTradePrice.minus(decreaseAmount);
    }
    if (order.type === OrderTypeEnum.MARKET && order.side === OrderSideEnum.SELL) {
      order.price = sellPrice;
    }
    if (order.type === OrderTypeEnum.MARKET && order.side === OrderSideEnum.BUY) {
      order.price = buyPrice;
    }

    await this.updateMarketPrice(buyPrice, sellPrice);

    if (order_type == "CANCEL") {
      console.log("cancel", order);
      // فلگ cancel تا taker وسط کنسل اجرا نشود
      this.cancelling = true;
      try {
        return await this.cancelOrder(order);
      } finally {
        this.cancelling = false;
      }
    }

    // TAKEN دیگر از Kafka نمی‌آید؛ Runner با secount خودش اجرا می‌کند
    if (order_type == "TAKEN") {
      console.log(
        `TAKEN from Kafka ignored for ${this.pair} — handled by OrderTakerBot loop`,
      );
      return;
    }

    return new Promise<void>((resolve) => {
      this.queue.push({ order, resolve });
      this.process();
    });
  }

  private updateMarketPrice(buyPrice, sellPrice) {
    this.buyOrders.forEach((item) => {
      if (item.type === OrderTypeEnum.MARKET && item.side === OrderSideEnum.BUY) {
        item.price = buyPrice;
      }
    });

    this.sellOrders.forEach((item) => {
      if (
        item.type === OrderTypeEnum.MARKET &&
        item.side === OrderSideEnum.SELL
      ) {
        item.price = sellPrice;
      }
    });
  }

  private async process() {
    if (this.processing) return;
    this.processing = true;

    while (this.queue.length > 0) {
      const item = this.queue.shift();
      if (!item) break;

      try {
        console.log("lastTradePrice", this.lastTradePrice);

        const order = item.order;
        const validation = await this.orderValidationService.validate(order);
        console.log("validation", validation);
        if (!validation.valid) {
          continue;
        }

        const isActive = await this.orderActivationService.handleNewOrder(
          order,
          this.lastTradePrice,
        );

        console.log("isActive", isActive, this.lastTradePrice, order.price);
        if (!isActive) {
          await this.publishOrderBook();
          await this.publishUserOrders(order);
          continue;
        }

        await this.tryMatchOrBook(order);

        this.orderActivationService.processPriceUpdate(
          this.pair,
          this.lastTradePrice,
          this.queue,
        );
      } catch (error) {
        console.error("Engine error:", error);
      } finally {
        item.resolve();
      }
    }

    this.processing = false;
  }

  private async tryMatchOrBook(order: IOrderPayload): Promise<boolean> {
    const tradeResult = await this.matchingService.match(
      this.pair,
      order,
      this.buyOrders,
      this.sellOrders,
      this.trades,
      this.lastTradePrice,
    );

    if (tradeResult.lastTradePrice) {
      this.lastTradePrice = new Decimal(tradeResult.lastTradePrice);
    }

    const remaining = new Decimal(order.amount).minus(
      new Decimal(order.filled_amount || 0),
    );
    if (remaining.gt(0)) {
      console.log("order.amount", order.amount);
      console.log("order.filled_amount", order.filled_amount);
      console.log("remaining", remaining);
      await this.addToOrderBook(order);
    }

    this.buyOrders = this.buyOrders.filter(
      (item) => item.status !== OrderStatusEnum.DONE,
    );
    this.sellOrders = this.sellOrders.filter(
      (item) => item.status !== OrderStatusEnum.DONE,
    );
    await this.publishOrderBook();

    return !!tradeResult.lastTradePrice;
  }

  private addToOrderBook(order: IOrderPayload) {
    const isBuy = order.side === 1;
    const book = isBuy ? this.buyOrders : this.sellOrders;

    const exists = book.find((o) => o.id === order.id);
    if (exists) {
      console.log("Duplicate order prevented:", order.id);
      return;
    }

    book.push(order);

    if (isBuy) {
      book.sort((a, b) => Number(b.price) - Number(a.price));
    } else {
      book.sort((a, b) => Number(a.price) - Number(b.price));
    }
  }

  private async publishOrderBook() {
    console.log("publishOrderBook");
    const stopBuys: IOrderPayload[] =
      this.orderActivationService.getPendingBuysOrders(this.pair);
    const stopSells: IOrderPayload[] =
      this.orderActivationService.getPendingSellsOrders(this.pair);

    const mergeBuys = this.buyOrders.concat(stopBuys);
    const buyersTrades = mergeBuys.map((t) => ({
      price: t.price,
      amount: t.amount,
      timestamp: t.created_at,
      pair: this.pair,
      filled_amount: t.filled_amount,
      side: t.side,
      total: t.total,
    }));

    const mergeSelles = this.sellOrders.concat(stopSells);
    const sellerTrades = mergeSelles.map((t) => ({
      price: t.price,
      amount: t.amount,
      timestamp: t.created_at,
      pair: this.pair,
      filled_amount: t.filled_amount,
      side: t.side,
      total: t.total,
    }));
    const done_date = new Date().toISOString();

    const doneTrades = this.trades.map((t) => ({
      price: t.price,
      amount: t.amount,
      timestamp: done_date,
      pair: this.pair,
      side: t.side,
      done_date: done_date,
    }));
    const payload = {
      timestamp: Date.now().toString(),
      last: doneTrades.length
        ? doneTrades.at(-1)?.price
        : this.lastTradePrice.toString(),
      buyers: buyersTrades,
      sellers: sellerTrades,
      done: doneTrades,
    };

    await this.producer.send({
      topic: "real-orders",
      messages: [{ key: this.pair, value: JSON.stringify(payload) }],
    });
  }

  private async publishUserOrders(order) {
    const payload = {
      timestamp: Date.now().toString(),
      pair: this.pair,
      user_id: order.user_id,
      order: order,
    };

    await this.producer.send({
      topic: "my-orders",
      messages: [
        {
          key: `${order.user_id.toString()}-${this.pair}`,
          value: JSON.stringify(payload),
        },
      ],
    });
  }

  private async cancelOrder(order) {
    this.buyOrders = this.buyOrders.filter((item) => item.id !== order.id);
    this.sellOrders = this.sellOrders.filter((item) => item.id !== order.id);
    this.orderActivationService.removeOrderFromPending(order.pairs, order.id);
    this.publishOrderBook();
    order.status = OrderStatusEnum.CANCELED;
    this.publishUserOrders(order);
    try {
      await this.producer.send({
        topic: "spot-trades",
        messages: [{ value: JSON.stringify({ id: order.id, type: "CANCEL" }) }],
      });
      console.log("Trade sent cancel  Kafka:", order.id);
    } catch (error) {
      console.error("Error sending trade to Kafka:", error);
    }
  }
}
