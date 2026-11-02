import { IOrder } from "./order.interface";

export interface ISelectedOrder {
  id: number;
  userId: number;
  side: number;
  type: number;
  pair: string;
  price: number; 
  amount: number;
  filledAmount: number;
  remainingAmount: number;  
  marketPrice: number;
  status: number;

  originalOrder: IOrder;
}