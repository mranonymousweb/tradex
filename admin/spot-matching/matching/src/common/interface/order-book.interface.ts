import { IOrder } from "./order.interface";

export interface IOrderBook {
    sellers: IOrder[],
    buyers: IOrder[]
}