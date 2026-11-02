import { Inject, Injectable, OnModuleInit } from "@nestjs/common";
import { IOrderPayload } from "src/common/interface/order-payload.interface";
import { CoreService } from "src/core/core.service";
import Decimal from "decimal.js";
import { Consumer, Kafka } from "kafkajs";
import { KAFKA_CLIENT } from "src/kafka/constant/kafka-client.constant";
import { FeeCalculationService } from "./fee-calculation.service";
import { OrderSideEnum, OrderStatusEnum, OrderTypeEnum } from "src/common/enum/order.enum";


// type MatchResult = {
//   lastTradePrice?: string;
// };
type MatchResult = {
  lastTradePrice?: string;
  trades: any[];
};
@Injectable()
export class MatchingService implements OnModuleInit {
   private producer;

// private lastTradePriceInternal: Decimal = new Decimal(0);
  constructor(
    @Inject(KAFKA_CLIENT) private readonly kafka: Kafka,
    private readonly coreService: CoreService,
    private readonly feeCalculationService: FeeCalculationService,
  ) {
    this.producer = this.kafka.producer();
    this.producer.connect().then(() => {
      console.log("Kafka producer connected in constructor!");
    });
  }


  async onModuleInit() {
    // this.producer = this.kafka.producer();
    // await this.producer.connect();
  }

async match(
  pair: string,
  taker: IOrderPayload,
  buyOrders: IOrderPayload[],
  sellOrders: IOrderPayload[],
  trades: any[],
  lastTradePrice: Decimal
): Promise<MatchResult> {

  console.log("lastTradePrice", lastTradePrice)
 
       // 🔥 Publish به Kafka
  const isBuy = taker.side === OrderSideEnum.BUY;
  let oppositeBook = isBuy ? sellOrders : buyOrders;

  let remainingAmount = new Decimal(taker.amount)
    .minus(new Decimal(taker.filled_amount || 0));

  let newLastTradePrice: Decimal | undefined;

  while (remainingAmount.gt(0)) {
    console.log("while (remainingAmount.gt(0)) {")

    let maker: IOrderPayload | undefined;

    if (taker.type === OrderTypeEnum.MARKET) {
      
      // MARKET / STOP MARKET: اولین سفارش قابل معامله
      maker = oppositeBook.find(m => m.status !== OrderStatusEnum.DONE && m.user_id !== taker.user_id);
    } else {
      // LIMIT / STOP LIMIT: بهترین قیمت قابل معامله
      const sortedBook = [...oppositeBook]
        .filter(m => m.status !== OrderStatusEnum.DONE && m.user_id !== taker.user_id)
        .sort((a, b) => isBuy ?
          new Decimal(a.price).minus(new Decimal(b.price)).toNumber() : // کمترین فروش برای خرید
          new Decimal(b.price).minus(new Decimal(a.price)).toNumber()   // بیشترین خرید برای فروش
        );
        console.log("sortedBook", sortedBook)
      maker = sortedBook.find(m => this.canMatch(taker, m, lastTradePrice));
      console.log("maker is: ", maker)
    }

    if (!maker) break; // هیچ سفارش قابل معامله نیست

    // check maker match count
    const makerRemaining = new Decimal(maker.amount)
      .minus(new Decimal(maker.filled_amount || 0));
      console.log("makerRemaining ", makerRemaining)
    if (makerRemaining.lte(0)) {
      maker.status = OrderStatusEnum.DONE;
      continue;
    }

    // get trade amount
    const tradeAmount = Decimal.min(remainingAmount, makerRemaining);
    console.log("tradeAmount ", tradeAmount)
    if (tradeAmount.lte(0)) break;

    const tradePrice = new Decimal(maker.price);
    newLastTradePrice = tradePrice;

    // ===== Update Taker =====
    taker.filled_amount = new Decimal(taker.filled_amount || 0)
      .plus(tradeAmount)
      .toString();
    taker.status =
      new Decimal(taker.filled_amount).equals(taker.amount)
        ? OrderStatusEnum.DONE
        : OrderStatusEnum.IN_PROGRESS;
    remainingAmount = remainingAmount.minus(tradeAmount);

    // ===== Update Maker =====
    if (tradeAmount.equals(makerRemaining)) {
      oppositeBook  = oppositeBook.filter(item => item.id !== maker.id)
      maker.status = OrderStatusEnum.DONE;
    } else {
      maker.filled_amount = new Decimal(maker.filled_amount || 0)
        .plus(tradeAmount)
        .toString();
      maker.status = OrderStatusEnum.IN_PROGRESS;
    }

    // ===== Fees =====
    const done_date = new Date().toISOString();
    taker.done_at =  done_date
    maker.done_at =  done_date
    const final = isBuy ? tradeAmount : tradeAmount.mul(tradePrice);
    const total = tradeAmount.mul(tradePrice);
   
    const takerFee = await this.feeCalculationService.calcFee(taker, tradeAmount, "taker",  taker.side === OrderSideEnum.BUY, tradePrice);
    const makerFee = await this.feeCalculationService.calcFee(maker, tradeAmount, "maker",  maker.side === OrderSideEnum.BUY, tradePrice);


    const trade = {
      type: "NEW",
      pair,
      price: tradePrice.toString(),
      amount: tradeAmount.toString(),
      total: total.toString(),
      buy_order_id: isBuy ? taker.id : maker.id,
      sell_order_id: isBuy ? maker.id : taker.id,
      timestamp: new Date(),
      done_at: done_date,
      taker: { id: taker.id, fee: takerFee.toString(), status: taker.status, meta: JSON.stringify(taker.meta)},
      maker: { id: maker.id, fee: makerFee.toString(), status: maker.status, meta: JSON.stringify(maker.meta)}
    };

    await this.sendTradeToKafka(trade);
    await this.sendUserOrderUpdate(taker);
    await this.sendUserOrderUpdate(maker);
    await trades.push(maker);
    await trades.push(taker);
    // فقط ۱۰ تای آخر رو نگه دار
    if (trades.length > 20) {
      trades.shift(); // قدیمی‌ترین رو حذف کن
    }

    lastTradePrice = tradePrice;
  }

  return {
    lastTradePrice: newLastTradePrice ? newLastTradePrice.toString() : lastTradePrice?.toString(), trades
  };
}
  


  private canMatch(taker: IOrderPayload, maker: IOrderPayload, lastTradePrice: Decimal): boolean {
    // 1️⃣ MARKET vs MARKET → فقط اگر lastTradePrice داشته باشیم
    if (taker.type === OrderTypeEnum.MARKET && maker.type === OrderTypeEnum.MARKET) {
      return lastTradePrice.gt(0);
    }

    // 2️⃣ یکی از طرفین MARKET → همیشه match
    if (taker.type === OrderTypeEnum.MARKET || maker.type === OrderTypeEnum.MARKET) return true;

    // 3️⃣ هر دو LIMIT → بررسی قیمت
    const takerPrice = new Decimal(taker.price);
    const makerPrice = new Decimal(maker.price);

    if (taker.side === OrderSideEnum.BUY) {
      // BUY → قیمت سفارش >= قیمت مقابل
      return takerPrice.greaterThanOrEqualTo(makerPrice);
    } else {
      // SELL → قیمت سفارش <= قیمت مقابل
      return takerPrice.lessThanOrEqualTo(makerPrice);
    }
  }


  async sendTradeToKafka(trade: any) {
    try {
      
      await this.producer.send({
        topic: 'spot-trades',
        messages: [{ value: JSON.stringify(trade) }],
      });
      console.log("Trade sent to Kafka:", trade);
    } catch (error) {
      console.error("Error sending trade to Kafka:", error);
    }
  }

  async sendUserOrderUpdate(order: IOrderPayload) {

    const payload = {
      timestamp: Date.now().toString(),
      pair: order.pairs,
      user_id: order.user_id,
      order: order
    };
    await this.producer.send({
      topic: 'my-orders',
      messages: [
        {
          key: `${order.user_id}-${order.pairs}`,
          value: JSON.stringify(payload),
        },
      ],
    });
  }

}
