import { Injectable } from "@nestjs/common";
import Decimal from "decimal.js";
import {
  OrderSideEnum,
  OrderStatusEnum,
  OrderTypeEnum,
} from "src/common/enum/order.enum";
import { IOrderPayload } from "src/common/interface/order-payload.interface";
import { Double } from "typeorm";

type QueueItem = {
  order: IOrderPayload;
  resolve: () => void;
};

@Injectable()
export class OrderActivationService {

  private tollerance = 0.05;
  /**
   * pair -> activation state
   */
  private activationBook = new Map<
    string,
    {
      stopBuy: IOrderPayload[];
      stopSell: IOrderPayload[];
    }
  >();

  // --------------------------------------------------
  // ENTRY POINT FOR NEW ORDER
  // --------------------------------------------------

  async handleNewOrder(
    order: IOrderPayload,
    currentMarketPrice: Decimal,
  ): Promise<boolean> {

    const shouldActivate = this.shouldActivate(order, currentMarketPrice);
    console.log("shouldActivate : ", shouldActivate)

    if (shouldActivate) {
      this.activate(order);
      return true;
    }

    this.addToWaiting(order);
    return false;
  }

  // --------------------------------------------------
  // CALLED AFTER TRADE / PRICE CHANGE
  // --------------------------------------------------

  processPriceUpdate(
    pair: string,
    newPrice: Decimal,
    queue: QueueItem[],
  ) {

    const state = this.activationBook.get(pair);
    if (!state) return;



    // 1️⃣ Activate STOP BUY (price >= stop)
    const remainingBuy: IOrderPayload[] = [];

    for (const order of state.stopBuy) {
          // get valid price
      let lowerLimit = new Decimal(Number(order.price) * (1 - this.tollerance))
      let upperLimit = new Decimal(Number(order.price) * (1 + this.tollerance))

      const stopPrice = new Decimal(order.stop_price);

      // if (newPrice.greaterThanOrEqualTo(stopPrice)) {
      if(newPrice <= upperLimit){
        // this.activate(order);
        queue.push({ order, resolve: () => {} });
      } else {
        remainingBuy.push(order);
      }
    }

    state.stopBuy = remainingBuy;

    // 2️⃣ Activate STOP SELL (price <= stop)
    const remainingSell: IOrderPayload[] = [];

    for (const order of state.stopSell) {
          // get valid price
      let lowerLimit = new Decimal(Number(order.price) * (1 - this.tollerance))
      let upperLimit = new Decimal(Number(order.price) * (1 + this.tollerance))

      const stopPrice = new Decimal(order.stop_price);

      // if (newPrice.lessThanOrEqualTo(stopPrice)) {
      if (newPrice >= lowerLimit) {
        // this.activate(order);
        queue.push({ order, resolve: () => {} });
      } else {
        remainingSell.push(order);
      }
    }

    state.stopSell = remainingSell;
  }

  // --------------------------------------------------
  // PURE ACTIVATION CHECK
  // --------------------------------------------------

  private shouldActivate(
    order: IOrderPayload,
    marketPrice: Decimal,
  ): boolean {


    switch (order.type) {

      case OrderTypeEnum.MARKET:
        return true;

      case OrderTypeEnum.LIMIT: {
        
        let lowerLimit = new Decimal(Number(order.price) * (1 - this.tollerance))
        let upperLimit = new Decimal(Number(order.price) * (1 + this.tollerance))
        const orderPrice = new Decimal(order.price);

        if (
          order.side === OrderSideEnum.BUY && 
          // (marketPrice >= lowerLimit) &&
           (marketPrice <= upperLimit)
          // marketPrice.lessThanOrEqualTo(orderPrice)
        ) return true;

        if (
          order.side === OrderSideEnum.SELL && (marketPrice >= lowerLimit)
          //  && (marketPrice <= upperLimit)
          // marketPrice.greaterThanOrEqualTo(orderPrice)
        ) return true;

        return false;
      }

      case OrderTypeEnum.STOP_LIMIT:
      case OrderTypeEnum.STOP_MARKET: {

        let lowerLimit = new Decimal(Number(order.stop_price) * (1 - this.tollerance))
        let upperLimit = new Decimal(Number(order.stop_price) * (1 + this.tollerance))
        const stopPrice = new Decimal(order.stop_price);

        //Stop Buy معمولاً برای خرید در روند صعودی (Breakout) استفاده میشه:
        if (
          order.side === OrderSideEnum.BUY && (marketPrice >= lowerLimit) 
          // && (marketPrice <= upperLimit)
          // marketPrice.greaterThanOrEqualTo(stopPrice)
        ) return true;

        // stop sell برای فروش در روند نزولی
        if (
          order.side === OrderSideEnum.SELL && 
          // (marketPrice >= lowerLimit) && 
           (marketPrice <= upperLimit)
          // marketPrice.lessThanOrEqualTo(stopPrice)
        ) return true;

        return false;
      }

      default:
        return false;
    }
  }

  // --------------------------------------------------
  // WAITING MANAGEMENT
  // --------------------------------------------------

  private addToWaiting(order: IOrderPayload) {

    // گر ارز داخل لیست نباشه لیست رو براش میسازه
    if (!this.activationBook.has(order.pairs)) {
      this.activationBook.set(order.pairs, {
        stopBuy: [],
        stopSell: [],
      });
    }

    // ارز رو از لیست میگیره
    const state = this.activationBook.get(order.pairs)!;

    // سفارش رو به لیست  ارز اضافه میکنه
    if (order.side === OrderSideEnum.BUY) {
      state.stopBuy.push(order);
    } else {
      state.stopSell.push(order);
    }
   
  }

  private activate(order: IOrderPayload) {
    order.status = OrderStatusEnum.ACTIVE;
    order.open_at = new Date().toISOString();
  }

  getPendingBuysOrders(pair){
    const item = this.activationBook.get(pair);
    if(!item)
      return []
    if(!item.stopBuy)
      return []

    return item.stopBuy
  }

  getPendingSellsOrders(pair){
    const item = this.activationBook.get(pair);
    if(!item)
      return []
    if(!item.stopSell)
      return []

    return item.stopSell
  }


  removeOrderFromPending(pair, orderId){
    const item = this.activationBook.get(pair);
    if(!item)
      return true
    if(item.stopBuy)
      item.stopBuy  = item.stopBuy.filter(item => item.id !== orderId)

    if(item.stopSell)
      item.stopBuy  = item.stopSell.filter(item => item.id !== orderId)

    return true
  }

 setWaitingList(orders: IOrderPayload[], pair, side) {

    // گر ارز داخل لیست نباشه لیست رو براش میسازه
    if (!this.activationBook.has(pair)) {
      this.activationBook.set(pair, {
        stopBuy: [],
        stopSell: [],
      });
    }

    // ارز رو از لیست میگیره
    const state = this.activationBook.get(pair)!;

    // سفارش رو به لیست  ارز اضافه میکنه
    if (side === OrderSideEnum.BUY) {
      state.stopBuy = orders;
    } else {
      state.stopSell = orders;
    }
   
  }
}
