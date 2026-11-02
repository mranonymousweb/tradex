import { Injectable } from '@nestjs/common';
import Decimal from 'decimal.js';

export interface OrderTrade {
  _id?: string;
  price: string;
  amount: string;
  filled_amount?: string;
  total?: string;
  side: number;
  pair?: string;
  timestamp?: string;
  done_date?: string;
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
export class OrderMergeService {
  private lastOrders = new Map<
    string,
    {
      real: { buyers: OrderTrade[]; sellers: OrderTrade[] };
      fake: { buyers: OrderTrade[]; sellers: OrderTrade[] };
      doneOrders: OrderTrade[];
    }
  >();

  private readonly MAX_ORDERS = 50;

  async buildMerged(
    pair: string,
    buyers: OrderTrade[],
    sellers: OrderTrade[],
    doneOrders: OrderTrade[],
    last: string,     // این last ورودی ممکن است قدیمی باشد
    from: string
  ): Promise<MergedResult> {
    if (!this.lastOrders.has(pair)) {
      this.lastOrders.set(pair, {
        real: { buyers: [], sellers: [] },
        fake: { buyers: [], sellers: [] },
        doneOrders: []
      });
    }

    const state = this.lastOrders.get(pair)!;

    // === جایگزینی کامل سفارشات خرید و فروش ===
    
    if (from === 'real-orders') {
      state.real.buyers = this.mergePrice(buyers || [], true);
      state.real.sellers = this.mergePrice(sellers || [], false);
      state.doneOrders = state.doneOrders.filter(order => order._id);
    } else {
      state.fake.buyers = this.mergePrice(buyers || [], true);
      state.fake.sellers = this.mergePrice(sellers || [], false);
      state.doneOrders = state.doneOrders.filter(order => !order._id);
    }

    // test
  
    // اضافه کردن doneOrders جدید به انتها
    this.appendDoneOrders(state, doneOrders || []);

    // === بخش مهم: گرفتن ۵۰ تای آخر + معکوس کردن ترتیب ===
    let finalDoneList = state.doneOrders.length > this.MAX_ORDERS 
      ? state.doneOrders.slice(-this.MAX_ORDERS)   // ۵۰ تای جدیدترین
      : state.doneOrders;

    // معکوس کردن ترتیب → جدیدترین در اول آرایه
    // finalDoneList = [...finalDoneList].reverse();

    // last و side از اولین آیتم لیست نهایی (جدیدترین معامله)
    const displayDone = finalDoneList.length > 0 ? finalDoneList[0] : null;

    const finalLastTradePrice = displayDone ? displayDone.price : last || '0';
    const finalSide = displayDone ? displayDone.side : 0;

    // مرج buyers و sellers
    const mergedBuyers = this.mergeTwoGroups(state.real.buyers, state.fake.buyers, true)
      .slice(0, this.MAX_ORDERS);

    const mergedSellers = this.mergeTwoGroups(state.real.sellers, state.fake.sellers, false)
      .slice(0, this.MAX_ORDERS);

    return {
      last: finalLastTradePrice,
      side: finalSide,
      from,
      buyers: mergedBuyers,
      sellers: mergedSellers,
      done: finalDoneList,        // جدیدترین در اول، قدیمی‌ترین در آخر
    };
  }

  // اضافه کردن doneOrders جدید به انتهای لیست (جدیدترین‌ها در آخر)
  private appendDoneOrders(state: any, newDoneOrders: OrderTrade[]) {
    if (!Array.isArray(newDoneOrders) || newDoneOrders.length === 0) return;

    for (const order of newDoneOrders) {
      // جلوگیری از اضافه شدن تکراری (اختیاری اما توصیه می‌شود)
      const key = this.getDoneKey(order);
      if (!state.doneOrders.some((existing: OrderTrade) => this.getDoneKey(existing) === key)) {
        state.doneOrders.push({ ...order });
      }
    }

    // فقط ۵۰ تای آخر را نگه دار
    if (state.doneOrders.length > this.MAX_ORDERS) {
      state.doneOrders = state.doneOrders.slice(-this.MAX_ORDERS);
    }
  }

  private getDoneKey(order: OrderTrade): string {
    const time = order.done_date || order.timestamp || '';
    return `${order.price}-${order.side}-${time}`;
  }

  private mergeTwoGroups(
    group1: OrderTrade[],
    group2: OrderTrade[],
    isBuy: boolean
  ): OrderTrade[] {
    const map = new Map<string, OrderTrade>();

    [...group1, ...group2].forEach(item => {
      const priceKey = item.price;

      if (map.has(priceKey)) {
        const existing = map.get(priceKey)!;
        existing.amount = new Decimal(existing.amount || 0)
          .plus(item.amount || 0)
          .toString();

        existing.filled_amount = new Decimal(existing.filled_amount || 0)
          .plus(item.filled_amount || 0)
          .toString();
      } else {
        map.set(priceKey, { ...item });
      }
    });

    return Array.from(map.values()).sort((a, b) =>
      isBuy ? Number(b.price) - Number(a.price) : Number(a.price) - Number(b.price)
    );
  }

  private mergePrice(arr: OrderTrade[], isBuy: boolean): OrderTrade[] {
    const map = new Map<string, OrderTrade>();

    for (const item of arr) {
      const priceKey = item.price;
      if (map.has(priceKey)) {
        const existing = map.get(priceKey)!;
        existing.amount = new Decimal(existing.amount || 0)
          .plus(item.amount || 0)
          .toString();

        existing.filled_amount = new Decimal(existing.filled_amount || 0)
          .plus(item.filled_amount || 0)
          .toString();
      } else {
        map.set(priceKey, { ...item });
      }
    }

    return Array.from(map.values()).sort((a, b) =>
      isBuy ? Number(b.price) - Number(a.price) : Number(a.price) - Number(b.price)
    );
  }
}