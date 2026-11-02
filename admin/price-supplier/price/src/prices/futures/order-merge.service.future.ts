import { Injectable } from '@nestjs/common';

export interface OrderTrade {
  _id?: string;
  price: string;
  amount: string;
  filled_amount?: string;
  total?: string;
  side: number;
  pair?: string;
  timestamp?: string;
  opened_at?: string;
}

export interface MergedResult {
  side: number;
  last: string;
  from: string;
  buyers: OrderTrade[];
  sellers: OrderTrade[];
  done: OrderTrade[];
}

@Injectable()
export class OrderMergeServiceFuture {
  private lastOrders = new Map<
    string,
    {
      real: { buyers: OrderTrade[]; sellers: OrderTrade[]; done: OrderTrade[] };
      fake: { buyers: OrderTrade[]; sellers: OrderTrade[]; done: OrderTrade[] };
    }
  >();

  private readonly MAX_ORDERS = 50;

  async buildMerged(
    pair: string,
    buyers: OrderTrade[],
    sellers: OrderTrade[],
    doneOrders: OrderTrade[],
    last: string,
    from: string,
  ): Promise<MergedResult> {
    if (!this.lastOrders.has(pair)) {
      this.lastOrders.set(pair, {
        real: { buyers: [], sellers: [], done: [] },
        fake: { buyers: [], sellers: [], done: [] },
      });
    }

    const state = this.lastOrders.get(pair)!;

    // جایگزینی کامل داده‌های هر منبع
    if (from === 'gold-real-orders') {
      state.real.buyers = buyers ?? [];
      state.real.sellers = sellers ?? [];
      state.real.done = doneOrders ?? [];
    } else {
      state.fake.buyers = buyers ?? [];
      state.fake.sellers = sellers ?? [];
      state.fake.done = doneOrders ?? [];
    }

    // buyers: real + fake، مرتب صعودی بر اساس قیمت
    const mergedBuyers = [...state.real.buyers, ...state.fake.buyers]
      .sort((a, b) => Number(a.price) - Number(b.price))
      .slice(0, this.MAX_ORDERS);

    // sellers: real + fake، مرتب نزولی بر اساس قیمت
    const mergedSellers = [...state.real.sellers, ...state.fake.sellers]
      .sort((a, b) => Number(b.price) - Number(a.price))
      .slice(0, this.MAX_ORDERS);

    // done: real + fake، مرتب نزولی بر اساس تاریخ
    const finalDoneList = [...state.real.done, ...state.fake.done]
      .sort((a, b) => this.getTime(b) - this.getTime(a))
      .slice(0, this.MAX_ORDERS);

    const displayDone = finalDoneList[0] ?? null;

    return {
      last: displayDone?.price ?? last ?? '0',
      side: displayDone?.side ?? 0,
      from,
      buyers: mergedBuyers,
      sellers: mergedSellers,
      done: finalDoneList,
    };
  }

  private getTime(order: OrderTrade): number {
    return new Date(order.opened_at || order.timestamp || 0).getTime();
  }
}
