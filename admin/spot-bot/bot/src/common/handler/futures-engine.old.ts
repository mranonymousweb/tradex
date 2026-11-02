import Decimal from 'decimal.js';
import { randomUUID } from 'crypto';
import { IBotPayload } from '../interface/bot-payload.interface';

type Side = 'buy' | 'sell';
type PositionSide = 'long' | 'short';

interface IOrder {
  _id: string;
  userId: string;
  side: Side;
  price: number;
  size: number;
  remaining: number;
  opened_at: number;
}

interface ITrade {
  _id: string, 
  price: number;
  size: number;
  side: Side;
  opened_at: number;
}

interface IPosition {
  userId: string;
  side: PositionSide;
  size: number;
  entryPrice: number;
  leverage: number;
  margin: number;
  liquidationPrice: number;
  pnl: number;
}

export class FuturesEngineOld {
  private bids: Map<number, IOrder[]> = new Map();
  private asks: Map<number, IOrder[]> = new Map();

  private positions: Map<string, IPosition> = new Map();
  private trades: ITrade[] = [];

  private lastPrice = 0;
  private markPrice = 0;
  private indexPrice = 0;

  private fundingRate = 0;

  private DONE_LIMIT = 20;

  private buyPercent = 0.001;
  private sellPercent = 0.001;
  private payload;
  private  mostAmount = 0;
  private  leastAmount = 0;
  private  marketPrice = 0;


  constructor(initialPrice: number) {
    this.lastPrice = initialPrice;
    this.indexPrice = initialPrice;
    this.markPrice = initialPrice;
  }

  // =========================
  // 📘 ORDER ENTRY
  // =========================
  placeLimitOrder(userId: string, side: Side, price: number, size: number, payload: IBotPayload,
    mostAmount: number,
    leastAmount: number,
    marketPrice: number
  ) {
    this.mostAmount = mostAmount
    this.leastAmount = leastAmount
    this.marketPrice = marketPrice
    this.buyPercent = payload.buyPercent
    this.sellPercent = payload.sellPercent
    this.payload = payload
    const order: IOrder = {
      _id: randomUUID(),
      userId,
      side,
      price,
      size,
      remaining: size,
      opened_at: Date.now(),
    };


    if (side === 'buy') {
      this.matchBuy(order);
      if (order.remaining > 0) this.addToBook(this.bids, order);
    } else {
      this.matchSell(order);
      if (order.remaining > 0) this.addToBook(this.asks, order);
    }
  }

  // =========================
  // ⚙️ MATCHING ENGINE
  // =========================
  private matchBuy(order: IOrder) {
    const prices = [...this.asks.keys()].sort((a, b) => a - b);

    for (const price of prices) {
      if (price > order.price || order.remaining <= 0) break;

      const queue = this.asks.get(price)!;

      while (queue.length && order.remaining > 0) {
        const top = queue[0];
        const fill = Math.min(order.remaining, top.remaining);

        this.executeTrade(order, top, price, fill, "buy");

        order.remaining -= fill;
        top.remaining -= fill;

        if (top.remaining === 0) queue.shift();
      }

      if (queue.length === 0) this.asks.delete(price);
    }
  }

  private matchSell(order: IOrder) {
    const prices = [...this.bids.keys()].sort((a, b) => b - a);

    for (const price of prices) {
      if (price < order.price || order.remaining <= 0) break;

      const queue = this.bids.get(price)!;

      while (queue.length && order.remaining > 0) {
        const top = queue[0];
        const fill = Math.min(order.remaining, top.remaining);

        this.executeTrade(top, order, price, fill, "sell");

        order.remaining -= fill;
        top.remaining -= fill;

        if (top.remaining === 0) queue.shift();
      }

      if (queue.length === 0) this.bids.delete(price);
    }
  }

  // =========================
  // 💥 TRADE EXECUTION
  // =========================
 
private executeTrade(
  buy: IOrder,
  sell: IOrder,
  price: number,
  size: number,
  takerSide: Side
) {
  this.lastPrice = price;
  const now = Date.now();
  const notional =
      this.leastAmount +
      Math.random() * (this.mostAmount - this.leastAmount);

   size = notional / price;
   const _id= randomUUID();;
  // 👇 یک معامله واقعی و ساده (بدون fake split)
  const trade: ITrade = {
    _id,
    price,
    size,
    side: takerSide, // می‌تونی حذفش کنی چون direction واقعی از buy/sell معلومه
    opened_at: now,
  };


  this.trades.unshift(trade);

  if (this.trades.length > this.DONE_LIMIT) {
    this.trades.length = this.DONE_LIMIT;
  }

  // مهم: size واقعی نه fake
  this.updatePosition(buy.userId, 'long', price, size);
  this.updatePosition(sell.userId, 'short', price, size);
}
  // =========================
  // 📊 POSITION ENGINE
  // =========================
  private updatePosition(
    userId: string,
    side: PositionSide,
    price: number,
    size: number
  ) {
    const key = `${userId}_${side}`;
    let pos = this.positions.get(key);

    if (!pos) {
      pos = {
        userId,
        side,
        size,
        entryPrice: price,
        leverage: 10,
        margin: (size * price) / 10,
        liquidationPrice: 0,
        pnl: 0,
      };
    } else {
      const newSize = pos.size + size;
      pos.entryPrice =
        (pos.entryPrice * pos.size + price * size) / newSize;

      pos.size = newSize;
    }

    pos.liquidationPrice = this.calculateLiq(pos);
    this.positions.set(key, pos);
  }

  private calculateLiq(pos: IPosition) {
    const mmr = 0.005;

    if (pos.side === 'long') {
      return pos.entryPrice * (1 - 1 / pos.leverage + mmr);
    } else {
      return pos.entryPrice * (1 + 1 / pos.leverage - mmr);
    }
  }

  // =========================
  // 📉 MARK PRICE
  // =========================
  updateMarkPrice() {
    this.markPrice =
      this.indexPrice * 0.7 + this.lastPrice * 0.3;
  }

  // =========================
  // 💰 PnL
  // =========================
  updatePnL() {
    this.positions.forEach((pos) => {
      const diff =
        pos.side === 'long'
          ? this.markPrice - pos.entryPrice
          : pos.entryPrice - this.markPrice;

      pos.pnl = diff * pos.size;
    });
  }

  // =========================
  // 💣 LIQUIDATION
  // =========================
  runLiquidation() {
    this.positions.forEach((pos, key) => {
      if (
        (pos.side === 'long' && this.markPrice <= pos.liquidationPrice) ||
        (pos.side === 'short' && this.markPrice >= pos.liquidationPrice)
      ) {
        this.positions.delete(key);
      }
    });
  }

  // =========================
  // 💸 FUNDING
  // =========================
  applyFunding() {
    this.fundingRate = Math.max(
      Math.min(
        (this.markPrice - this.indexPrice) / this.indexPrice,
        0.01
      ),
      -0.01
    );

    this.positions.forEach((pos) => {
      const payment = pos.size * this.fundingRate;

      if (pos.side === 'long') pos.margin -= payment;
      else pos.margin += payment;
    });
  }

  // =========================
  // 📦 ORDERBOOK
  // =========================
  private addToBook(
    book: Map<number, IOrder[]>,
    order: IOrder
  ) {
    if (!book.has(order.price)) book.set(order.price, []);
    book.get(order.price)!.push(order);
  }

  // =========================
  // 📊 UI VIEW (buyers/sellers)
  // =========================
private buildDepth(book: Map<number, IOrder[]>, isBuy: boolean, limit: number, maxItems = 50) {
  const prices = [...book.keys()].sort((a, b) =>
    isBuy ? b - a : a - b
  );

  let sum = 0;

  return prices.slice(0, Math.min(limit, maxItems)).map((price) => {
    const orders = book.get(price)!;
    const amount = orders.reduce((s, o) => s + o.remaining, 0);

    sum += amount;

    return {
      _id: randomUUID(),
      price: +price.toFixed(6),
      amount: +amount.toFixed(6),
      total: +(price * amount).toFixed(6),
      sum: +sum.toFixed(6),
      side: isBuy ? 1 : 2,  // ← side اضافه شد
    };
  });
}

getBuyers(limit = 10) {
  const data = this.buildDepth(this.bids, true, limit, 50);

  return this.ensureMinDepth(data, true, limit);
}

getSellers(limit = 10) {
  const data = this.buildDepth(this.asks, false, limit, 50);

  return this.ensureMinDepth(data, false, limit);
}

// =========================
// 📊 DONE TRADES
// =========================

getDones(maxItems = 50) {
  return this.trades.slice(0, maxItems).map((t) => ({
    _id: randomUUID(),
    price: +t.price.toFixed(6),
    amount: +t.size, // ❗ مستقیم، بدون toFixed خطرناک
    total: +(t.price * t.size),
    side: t.side == 'buy' ? 1 : 2,
    opened_at: t.opened_at,
  }));
}

getMarketView(limit = 10, maxItems = 50) {
  return {
    buyers: this.getBuyers(limit),
    sellers: this.getSellers(limit),
    done: this.getDones(maxItems),
  };
}

  // جدید: شبیه‌سازی معاملات بین buy/sell
private matchOrders() {
  let pricesBid = [...this.bids.keys()].sort((a, b) => b - a);
  let pricesAsk = [...this.asks.keys()].sort((a, b) => a - b);

  while (pricesBid.length && pricesAsk.length) {
    const bestBid = pricesBid[0];
    const bestAsk = pricesAsk[0];

    if (bestBid < bestAsk) break; // هیچ تداخلی باقی نمانده

    const bidQueue = this.bids.get(bestBid)!;
    const askQueue = this.asks.get(bestAsk)!;

    const bid = bidQueue[0];
    const ask = askQueue[0];

    const size = Math.min(bid.remaining, ask.remaining);

     // ✅ تعیین side برای done
    const takerSide: Side = Math.random() >= 0.5 ? 'buy' : 'sell';
      console.log("matchOrders", takerSide)

    this.executeTrade(bid, ask, bestAsk, size, takerSide);

    bid.remaining -= size;
    ask.remaining -= size;

    if (bid.remaining <= 0) bidQueue.shift();
    if (ask.remaining <= 0) askQueue.shift();

    if (bidQueue.length === 0) {
      this.bids.delete(bestBid);
      pricesBid.shift();
    }

    if (askQueue.length === 0) {
      this.asks.delete(bestAsk);
      pricesAsk.shift();
    }
  }
}

  // =========================
  // 🔁 TICK
  // =========================
  tick() {

      // ⭐ پاک کردن کامل orderbook
    this.bids.clear();
    this.asks.clear();

    this.seedOrders(); // (اگر داری تولید می‌کنی)

    // this.updateMarkPrice();
    // this.matchOrders(); // ← اضافه شده
    // this.updatePnL();
    // this.runLiquidation();
  }



private ensureMinDepth(
  data: any[],
  isBuy: boolean,
  limit: number
) {
  const result = [...data];

  const basePrice =
    result.length > 0
      ? result[0].price // ⭐ بهترین قیمت (top of book)
      : this.lastPrice;

  const percent = isBuy ? 0.001 : 0.001; // 🔥 اینو بعداً داینامیک کن

  const existing = new Set(result.map((d) => d.price));

  let i = 1;

  while (result.length < limit) {
    // ⭐ ladder بر اساس percent
    const price = isBuy
      ? basePrice * (1 - percent * i)
      : basePrice * (1 + percent * i);

    const fixed = +price.toFixed(6);

    // جلوگیری از قیمت تکراری
    if (!existing.has(fixed)) {
      const amount = Math.random() * 2 + 0.1;

      result.push({
        _id:randomUUID(),
        price: fixed,
        amount: +amount.toFixed(6),
        total: +(fixed * amount).toFixed(6),
        sum: 0,
        side: isBuy ? 1 : 2,
      });

      existing.add(fixed);
    }

    i++;
  }

  return result;
}



  private seedOrders() {
  const levels = 10;
  const base = this.lastPrice;


  for (let i = 1; i <= levels; i++) {
    const buyPrice = base * (1 - this.buyPercent * i);
    const sellPrice = base * (1 + this.sellPercent * i);
    const minPrice =  this.marketPrice * (1 - 0.002);
    const maxPrice =  this.marketPrice * (1 + 0.002);
    const price =
      minPrice + Math.random() * (maxPrice - minPrice);

    const notional =
      this.leastAmount +
      Math.random() * (this.mostAmount - this.leastAmount);

    const size = notional / price;
    this.placeLimitOrder(
      'mm',
      'buy',
      +buyPrice.toFixed(6),
      size,
      this.payload,
      this.mostAmount,
      this.leastAmount,
      this.marketPrice
    );

    this.placeLimitOrder(
      'mm',
      'sell',
      +sellPrice.toFixed(6),
      size,
      this.payload,
      this.mostAmount,
      this.leastAmount,
       this.marketPrice
    );
  }
}
}