import { IOrder, IOrderFuture } from "./order.interface";

export interface IBotOrder {
    sellers: IOrder[];
    buyers: IOrder[],
    done: IOrder[],
    last?: number;
    side?: number;
}

export interface IBotOrderFuture {
    sellers: IOrderFuture[];
    buyers: IOrderFuture[],
    done: IOrderFuture[],
    last?: number;
    side?: number;
}