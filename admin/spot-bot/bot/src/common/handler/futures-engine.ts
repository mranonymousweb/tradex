import Decimal from 'decimal.js';
import { randomUUID } from 'crypto';

type Side = 'buy' | 'sell';
type PositionSide = 'long' | 'short';

interface IOrder {
  _id: string;
  userId: string;
  side: Side;
  price: Decimal;
  size: Decimal;
  remaining: Decimal;
  opened_at: number;
  isMarketMaker?: boolean;
}

interface ITrade {
  _id: string;
  price: Decimal;
  size: Decimal;
  side: Side;
  opened_at: number;
}

interface IPosition {
  userId: string;
  side: PositionSide;
  size: Decimal;
  entryPrice: Decimal;
  leverage: Decimal;
  margin: Decimal;
  liquidationPrice: Decimal;
  pnl: Decimal;
}

interface IMarketView {
  buyers: DepthEntry[];
  sellers: DepthEntry[];
  done: TradeView[];
  lastPrice: Decimal;
  markPrice: Decimal;
  fundingRate: Decimal;
}

interface DepthEntry {
  _id: string;
  price: Decimal;
  amount: Decimal;
  total: Decimal;
  sum: Decimal;
  side: 1 | 2;
}

interface TradeView {
  _id: string;
  price: Decimal;
  amount: Decimal;
  total: Decimal;
  side: 1 | 2;
  opened_at: number;
}

export interface BotConfig {
  buyPercent: Decimal;
  sellPercent: Decimal;
  initialPrice: Decimal;
  leastAmount: Decimal;
  mostAmount: Decimal;
  marketPrice: Decimal;
}

export class FuturesEngine {
  private bids: Map<number, IOrder[]> = new Map();
  private asks: Map<number, IOrder[]> = new Map();

  private positions: Map<string, IPosition> = new Map();
  private trades: ITrade[] = [];

  private lastPrice: Decimal;
  private markPrice: Decimal;
  private indexPrice: Decimal;

  private fundingRate: Decimal = new Decimal(0);

  private readonly DONE_LIMIT = 50;
  private readonly BOOK_DEPTH_LIMIT = 50;
  private readonly MAX_BOOK_ORDERS = 100;

  private botConfig!: BotConfig;

  private readonly MARKET_MAKER_LEVELS = 12;
  private readonly MARKET_ORDERS_PER_TICK = 4;

  private nextTakerSide: Side = 'buy';

  constructor(initialPrice: Decimal) {
    Decimal.set({ precision: 20 });

    const price = new Decimal(initialPrice || 0);

    this.lastPrice = price;
    this.indexPrice = price;
    this.markPrice = price;
  }

  configure(config: BotConfig) {
    const marketPrice = new Decimal(config.marketPrice);

    this.botConfig = {
      buyPercent: this.normalizePercent(config.buyPercent),
      sellPercent: this.normalizePercent(config.sellPercent),
      initialPrice: new Decimal(config.initialPrice),
      leastAmount: new Decimal(config.leastAmount),
      mostAmount: new Decimal(config.mostAmount),
      marketPrice,
    };

    if (!marketPrice.isFinite() || marketPrice.lte(0)) {
      return;
    }

    this.indexPrice = marketPrice;

    if (!this.lastPrice || !this.lastPrice.isFinite() || this.lastPrice.lte(0)) {
      this.lastPrice = marketPrice;
    }

    if (!this.markPrice || !this.markPrice.isFinite() || this.markPrice.lte(0)) {
      this.markPrice = marketPrice;
    }
  }

  placeLimitOrder(
    userId: string,
    side: Side,
    price: Decimal.Value,
    size: Decimal.Value,
    isMarketMaker = false,
  ) {
    const orderPrice = new Decimal(price);
    const orderSize = new Decimal(size);

    if (!orderPrice.isFinite() || orderPrice.lte(0)) return;
    if (!orderSize.isFinite() || orderSize.lte(0)) return;

    const fixedPrice = new Decimal(orderPrice.toFixed(6));
    const fixedSize = new Decimal(orderSize.toFixed(8));

    const order: IOrder = {
      _id: randomUUID(),
      userId,
      side,
      price: fixedPrice,
      size: fixedSize,
      remaining: fixedSize,
      opened_at: Date.now(),
      isMarketMaker,
    };

    if (side === 'buy') {
      this.matchLimitBuy(order);

      if (order.remaining.gt(0)) {
        this.addToBook(this.bids, order);
      }
    } else {
      this.matchLimitSell(order);

      if (order.remaining.gt(0)) {
        this.addToBook(this.asks, order);
      }
    }
  }

  placeMarketOrder(userId: string, side: Side, size: Decimal.Value) {
    const orderSize = new Decimal(size);

    if (!orderSize.isFinite() || orderSize.lte(0)) return;

    if (side === 'buy') {
      this.matchMarketBuy(userId, orderSize);
    } else {
      this.matchMarketSell(userId, orderSize);
    }
  }

  private matchLimitBuy(order: IOrder) {
    const prices = [...this.asks.keys()].sort((a, b) => a - b);

    for (const priceNum of prices) {
      const price = new Decimal(priceNum);

      if (price.gt(order.price) || order.remaining.lte(0)) break;

      const queue = this.asks.get(priceNum);

      if (!queue) continue;

      while (queue.length && order.remaining.gt(0)) {
        const makerOrder = queue[0];
        const fillSize = Decimal.min(order.remaining, makerOrder.remaining);

        this.executeTrade(order, makerOrder, price, fillSize, 'buy');

        order.remaining = order.remaining.minus(fillSize);
        makerOrder.remaining = makerOrder.remaining.minus(fillSize);

        if (makerOrder.remaining.lte(0)) {
          queue.shift();
        }
      }

      if (!queue.length) {
        this.asks.delete(priceNum);
      }
    }
  }

  private matchLimitSell(order: IOrder) {
    const prices = [...this.bids.keys()].sort((a, b) => b - a);

    for (const priceNum of prices) {
      const price = new Decimal(priceNum);

      if (price.lt(order.price) || order.remaining.lte(0)) break;

      const queue = this.bids.get(priceNum);

      if (!queue) continue;

      while (queue.length && order.remaining.gt(0)) {
        const makerOrder = queue[0];
        const fillSize = Decimal.min(order.remaining, makerOrder.remaining);

        this.executeTrade(makerOrder, order, price, fillSize, 'sell');

        order.remaining = order.remaining.minus(fillSize);
        makerOrder.remaining = makerOrder.remaining.minus(fillSize);

        if (makerOrder.remaining.lte(0)) {
          queue.shift();
        }
      }

      if (!queue.length) {
        this.bids.delete(priceNum);
      }
    }
  }

  private matchMarketBuy(userId: string, size: Decimal) {
    let remaining = new Decimal(size);
    const prices = [...this.asks.keys()].sort((a, b) => a - b);

    const takerOrder: IOrder = {
      _id: randomUUID(),
      userId,
      side: 'buy',
      price: new Decimal('1e+20'),
      size,
      remaining: size,
      opened_at: Date.now(),
      isMarketMaker: false,
    };

    for (const priceNum of prices) {
      if (remaining.lte(0)) break;

      const price = new Decimal(priceNum);
      const queue = this.asks.get(priceNum);

      if (!queue) continue;

      while (queue.length && remaining.gt(0)) {
        const makerOrder = queue[0];
        const fillSize = Decimal.min(remaining, makerOrder.remaining);

        this.executeTrade(takerOrder, makerOrder, price, fillSize, 'buy');

        remaining = remaining.minus(fillSize);
        takerOrder.remaining = remaining;
        makerOrder.remaining = makerOrder.remaining.minus(fillSize);

        if (makerOrder.remaining.lte(0)) {
          queue.shift();
        }
      }

      if (!queue.length) {
        this.asks.delete(priceNum);
      }
    }
  }

  private matchMarketSell(userId: string, size: Decimal) {
    let remaining = new Decimal(size);
    const prices = [...this.bids.keys()].sort((a, b) => b - a);

    const takerOrder: IOrder = {
      _id: randomUUID(),
      userId,
      side: 'sell',
      price: new Decimal(0),
      size,
      remaining: size,
      opened_at: Date.now(),
      isMarketMaker: false,
    };

    for (const priceNum of prices) {
      if (remaining.lte(0)) break;

      const price = new Decimal(priceNum);
      const queue = this.bids.get(priceNum);

      if (!queue) continue;

      while (queue.length && remaining.gt(0)) {
        const makerOrder = queue[0];
        const fillSize = Decimal.min(remaining, makerOrder.remaining);

        this.executeTrade(makerOrder, takerOrder, price, fillSize, 'sell');

        remaining = remaining.minus(fillSize);
        takerOrder.remaining = remaining;
        makerOrder.remaining = makerOrder.remaining.minus(fillSize);

        if (makerOrder.remaining.lte(0)) {
          queue.shift();
        }
      }

      if (!queue.length) {
        this.bids.delete(priceNum);
      }
    }
  }

  private executeTrade(
    buy: IOrder,
    sell: IOrder,
    price: Decimal,
    size: Decimal,
    takerSide: Side,
  ) {
    this.lastPrice = new Decimal(price);

    const trade: ITrade = {
      _id: randomUUID(),
      price: new Decimal(price),
      size: new Decimal(size),
      side: takerSide,
      opened_at: Date.now(),
    };

    this.trades.unshift(trade);

    if (this.trades.length > this.DONE_LIMIT) {
      this.trades.length = this.DONE_LIMIT;
    }

    this.updatePosition(buy.userId, 'long', price, size, buy.isMarketMaker);
    this.updatePosition(sell.userId, 'short', price, size, sell.isMarketMaker);
  }

  private updatePosition(
    userId: string,
    side: PositionSide,
    price: Decimal,
    size: Decimal,
    isMarketMaker?: boolean,
  ) {
    if (isMarketMaker || userId === 'mm') return;

    const key = `${userId}_${side}`;
    let position = this.positions.get(key);

    if (!position) {
      position = {
        userId,
        side,
        size: new Decimal(size),
        entryPrice: new Decimal(price),
        leverage: new Decimal(10),
        margin: new Decimal(0),
        liquidationPrice: new Decimal(0),
        pnl: new Decimal(0),
      };
    } else {
      const currentSize = new Decimal(position.size);
      const newSize = currentSize.plus(size);

      if (newSize.lte(0)) return;

      const newEntryPrice = new Decimal(position.entryPrice)
        .times(currentSize)
        .plus(new Decimal(price).times(size))
        .div(newSize);

      position.size = newSize;
      position.entryPrice = newEntryPrice;
    }

    const positionValue = position.entryPrice.times(position.size);
    position.margin = positionValue.div(position.leverage);
    position.liquidationPrice = this.calculateLiq(position);

    this.positions.set(key, position);
  }

  private calculateLiq(position: IPosition): Decimal {
    const leverage = new Decimal(position.leverage);
    const entryPrice = new Decimal(position.entryPrice);
    const mmr = new Decimal(1).div(leverage);

    if (position.side === 'long') {
      return entryPrice.times(
        new Decimal(1).minus(new Decimal(1).div(leverage)).plus(mmr),
      );
    }

    return entryPrice.times(
      new Decimal(1).plus(new Decimal(1).div(leverage)).minus(mmr),
    );
  }

  updateMarkPrice() {
    const marketPrice = new Decimal(
      this.botConfig?.marketPrice ?? this.indexPrice ?? this.lastPrice,
    );

    if (!marketPrice.isFinite() || marketPrice.lte(0)) {
      return;
    }

    this.indexPrice = marketPrice;

    const safeLastPrice =
      this.lastPrice && this.lastPrice.isFinite() && this.lastPrice.gt(0)
        ? this.lastPrice
        : marketPrice;

    this.markPrice = marketPrice
      .times(new Decimal('0.7'))
      .plus(safeLastPrice.times(new Decimal('0.3')));
  }

  applyFunding() {
    if (!this.indexPrice || !this.indexPrice.isFinite() || this.indexPrice.lte(0)) {
      return;
    }

    const fundingRateRaw = this.markPrice.minus(this.indexPrice).div(this.indexPrice);

    this.fundingRate = Decimal.max(
      Decimal.min(fundingRateRaw, new Decimal('0.01')),
      new Decimal('-0.01'),
    );

    this.positions.forEach((position) => {
      const notional = position.size.times(this.markPrice);
      const payment = notional.times(this.fundingRate);

      if (position.side === 'long') {
        position.margin = position.margin.minus(payment);
        position.pnl = position.pnl.minus(payment);
      } else {
        position.margin = position.margin.plus(payment);
        position.pnl = position.pnl.plus(payment);
      }
    });
  }

  private addToBook(book: Map<number, IOrder[]>, order: IOrder) {
    const priceNum = Number(order.price.toFixed(6));

    if (!Number.isFinite(priceNum) || priceNum <= 0) {
      return;
    }

    if (!book.has(priceNum)) {
      book.set(priceNum, []);
    }

    book.get(priceNum)!.push(order);
  }

  private buildDepth(
    book: Map<number, IOrder[]>,
    isBuy: boolean,
    limit: number,
    maxOrders = this.MAX_BOOK_ORDERS,
  ): DepthEntry[] {
    const prices = [...book.keys()]
      .sort((a, b) => (isBuy ? b - a : a - b))
      .slice(0, maxOrders);

    const depth: DepthEntry[] = [];
    let cumulativeTotal = new Decimal(0);

    for (const priceNum of prices) {
      if (depth.length >= limit) break;

      const price = new Decimal(priceNum);
      const orders = (book.get(priceNum) ?? []).filter((order) =>
        new Decimal(order.remaining ?? 0).gt(0),
      );

      if (!orders.length) continue;

      let amount = new Decimal(0);

      for (const order of orders) {
        amount = amount.plus(order.remaining);
      }

      if (!amount.isFinite() || amount.lte(0)) continue;

      const total = price.times(amount);

      if (!total.isFinite() || total.lte(0)) continue;

      cumulativeTotal = cumulativeTotal.plus(total);

      depth.push({
        _id: `${isBuy ? 'buy' : 'sell'}-${price.toFixed(6)}`,
        price: new Decimal(price.toFixed(6)),
        amount: new Decimal(amount.toFixed(8)),
        total: new Decimal(total.toFixed(6)),
        sum: new Decimal(cumulativeTotal.toFixed(6)),
        side: isBuy ? 1 : 2,
      });
    }

    return depth;
  }

  private ensureMinDepth(
    data: DepthEntry[],
    isBuy: boolean,
    limit: number,
  ): DepthEntry[] {
    const result = [...data];
    const config = this.botConfig;

    if (!config) {
      return result;
    }

    const basePrice = new Decimal(config.marketPrice);

    if (!basePrice.isFinite() || basePrice.lte(0)) {
      return result;
    }

    const percent = this.normalizePercent(
      isBuy ? config.buyPercent : config.sellPercent,
    );

    const existingPrices = new Set(
      result.map((item) => new Decimal(item.price).toFixed(6)),
    );

    let cumulativeTotal =
      result.length > 0
        ? new Decimal(result[result.length - 1].sum ?? 0)
        : new Decimal(0);

    let level = 1;
    let attempts = 0;
    const maxAttempts = limit * 50;

    while (result.length < limit && attempts < maxAttempts) {
      attempts++;

      // const step = percent.times(level).div(limit);
      const randomPercent = percent.times(Math.random());
      const step = randomPercent
      .div(100)
      .times(basePrice)

      // const price = isBuy
      //   ? basePrice.times(new Decimal(1).minus(step))
      //   : basePrice.times(new Decimal(1).plus(step));

      const price = isBuy
        ? basePrice.minus(step)
        : basePrice.plus(step);

      level++;

      if (!price.isFinite() || price.lte(0)) {
        continue;
      }

      const fixedPrice = price.toFixed(6);

      if (existingPrices.has(fixedPrice)) {
        continue;
      }

      const notional = this.randomNotional();
      const amount = notional.div(price);
      const total = price.times(amount);

      if (!amount.isFinite() || amount.lte(0)) {
        continue;
      }

      cumulativeTotal = cumulativeTotal.plus(total);

      result.push({
        _id: `synthetic-${isBuy ? 'buy' : 'sell'}-${fixedPrice}`,
        price: new Decimal(fixedPrice),
        amount: new Decimal(amount.toFixed(8)),
        total: new Decimal(total.toFixed(6)),
        sum: new Decimal(cumulativeTotal.toFixed(6)),
        side: isBuy ? 1 : 2,
      });

      existingPrices.add(fixedPrice);
    }

    return result;
  }

  getBuyers(limit = this.BOOK_DEPTH_LIMIT): DepthEntry[] {
    const data = this.buildDepth(this.bids, true, limit);
    return this.ensureMinDepth(data, true, limit);
  }

  getSellers(limit = this.BOOK_DEPTH_LIMIT): DepthEntry[] {
    const data = this.buildDepth(this.asks, false, limit);
    return this.ensureMinDepth(data, false, limit);
  }

  getDones(maxItems = this.DONE_LIMIT): TradeView[] {
    return this.trades.slice(0, maxItems).map((trade): TradeView => {
      const price = new Decimal(trade.price);
      const amount = new Decimal(trade.size);
      const total = price.times(amount);

      return {
        _id: trade._id,
        price: new Decimal(price.toFixed(6)),
        amount: new Decimal(amount.toFixed(8)),
        total: new Decimal(total.toFixed(6)),
        side: trade.side === 'buy' ? 1 : 2,
        opened_at: trade.opened_at,
      };
    });
  }

  getMarketView(): IMarketView {
    return {
      buyers: this.getBuyers(),
      sellers: this.getSellers(),
      done: this.getDones(),
      lastPrice: new Decimal(this.lastPrice.toFixed(6)),
      markPrice: new Decimal(this.markPrice.toFixed(6)),
      fundingRate: new Decimal(this.fundingRate.toFixed(6)),
    };
  }

  private removeMarketMakerOrders() {
    for (const [priceNum, orders] of this.bids.entries()) {
      const filtered = orders.filter((order) => !order.isMarketMaker);

      if (filtered.length) {
        this.bids.set(priceNum, filtered);
      } else {
        this.bids.delete(priceNum);
      }
    }

    for (const [priceNum, orders] of this.asks.entries()) {
      const filtered = orders.filter((order) => !order.isMarketMaker);

      if (filtered.length) {
        this.asks.set(priceNum, filtered);
      } else {
        this.asks.delete(priceNum);
      }
    }
  }

  private seedOrders() {
    const config = this.botConfig;

    if (!config) return;

    const levels = this.MARKET_MAKER_LEVELS;
    const basePrice = new Decimal(config.marketPrice);

    if (!basePrice.isFinite() || basePrice.lte(0)) {
      return;
    }

    const buyPercent = this.normalizePercent(config.buyPercent);
    const sellPercent = this.normalizePercent(config.sellPercent);

    for (let level = 1; level <= levels; level++) {
      // const buyStep = buyPercent.times(level).div(levels);
      // const sellStep = sellPercent.times(level).div(levels);
      const randomBuyPercent = buyPercent.times(Math.random());
      const randomSellPercent = sellPercent.times(Math.random());
      const buyStep = randomBuyPercent
      .div(100)
      .times(basePrice)

      const sellStep = randomSellPercent
      .div(100)
      .times(basePrice)

      // const buyPrice = basePrice.times(new Decimal(1).minus(buyStep));
      // const sellPrice = basePrice.times(new Decimal(1).plus(sellStep));
      const buyPrice = basePrice.minus(buyStep);
      const sellPrice = basePrice.plus(sellStep);

      if (!buyPrice.isFinite() || buyPrice.lte(0)) continue;
      if (!sellPrice.isFinite() || sellPrice.lte(0)) continue;

      const buyNotional = this.randomNotional();
      const sellNotional = this.randomNotional();

      const buySize = buyNotional.div(buyPrice);
      const sellSize = sellNotional.div(sellPrice);

      this.placeLimitOrder('mm', 'buy', buyPrice, buySize, true);
      this.placeLimitOrder('mm', 'sell', sellPrice, sellSize, true);
    }
  }

  private getBalancedTakerSide(): Side {
    const recentTrades = this.trades.slice(0, 20);
    const buyCount = recentTrades.filter((trade) => trade.side === 'buy').length;
    const sellCount = recentTrades.filter((trade) => trade.side === 'sell').length;

    if (buyCount < sellCount) return 'buy';
    if (sellCount < buyCount) return 'sell';

    const side = this.nextTakerSide;
    this.nextTakerSide = this.nextTakerSide === 'buy' ? 'sell' : 'buy';

    return side;
  }

  private generateTakerOrder() {
    const side = this.getBalancedTakerSide();

    const referencePrice =
      this.lastPrice && this.lastPrice.isFinite() && this.lastPrice.gt(0)
        ? this.lastPrice
        : this.markPrice && this.markPrice.isFinite() && this.markPrice.gt(0)
          ? this.markPrice
          : this.indexPrice;

    if (!referencePrice || !referencePrice.isFinite() || referencePrice.lte(0)) {
      return;
    }

    const notional = this.randomNotional();
    const size = notional.div(referencePrice);

    this.placeMarketOrder('taker', side, size);
  }

  private generateTakerFlow(count = this.MARKET_ORDERS_PER_TICK) {
    for (let i = 0; i < count; i++) {
      this.generateTakerOrder();
    }
  }

  tick() {
    if (!this.botConfig) return;

    this.removeMarketMakerOrders();

    this.updateMarkPrice();

    this.seedOrders();

    this.generateTakerFlow();

    this.applyFunding();

    this.positions.forEach((position) => {
      position.pnl = this.calculatePnLForPosition(position);
    });

    this.runLiquidations();
  }

  private calculatePnLForPosition(position: IPosition): Decimal {
    const currentPrice = new Decimal(this.markPrice);
    const entryPrice = new Decimal(position.entryPrice);
    const size = new Decimal(position.size);

    if (position.side === 'long') {
      return size.times(currentPrice.minus(entryPrice));
    }

    return size.times(entryPrice.minus(currentPrice));
  }

  runLiquidations() {
    this.positions.forEach((position) => {
      const liquidationPrice = new Decimal(position.liquidationPrice);

      const shouldLiquidate =
        (position.side === 'long' && this.markPrice.lte(liquidationPrice)) ||
        (position.side === 'short' && this.markPrice.gte(liquidationPrice));

      if (shouldLiquidate) {
        this.positions.delete(`${position.userId}_${position.side}`);
      }
    });
  }

  private normalizePercent(value: Decimal.Value): Decimal {
    let percent = new Decimal(value ?? 0);

    if (!percent.isFinite() || percent.lte(0)) {
      return new Decimal('0.001');
    }

    if (percent.gte(1)) {
      percent = percent.div(100);
    } else if (percent.gt('0.05')) {
      percent = percent.div(100);
    }

    if (percent.gt('0.5')) {
      percent = new Decimal('0.5');
    }

    return percent;
  }

  private randomNotional(): Decimal {
    const config = this.botConfig;

    let min = new Decimal(config?.leastAmount ?? 100);
    let max = new Decimal(config?.mostAmount ?? 1000);

    if (!min.isFinite() || min.lte(0)) {
      min = new Decimal(100);
    }

    if (!max.isFinite() || max.lte(0)) {
      max = new Decimal(1000);
    }

    if (max.lt(min)) {
      const temp = min;
      min = max;
      max = temp;
    }

    if (max.eq(min)) {
      return min;
    }

    return Decimal.random().times(max.minus(min)).plus(min);
  }
}
