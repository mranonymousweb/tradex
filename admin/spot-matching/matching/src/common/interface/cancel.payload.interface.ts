import { OrderSideEnum } from "../enum/order.enum";

export interface ICancelPayload { id: number; userId: number; side: OrderSideEnum; }