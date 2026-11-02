export interface IOrder {
    _id: string;
    userId?: number;
    side: number;
    type: number;
    pair: string;
    price: number;
    amount: number;
    filled_amount: number;
    remainingAmount: number;
    marketPrice: string;
    total: number;
    timestamp: number;
    isFuture?: boolean;
}

export interface IOrderFuture {
    _id?: string;
    side?: number;
    pair?: string;
    price: number;
    total: number;
    amount: number;
    sum?: number;
    opened_at?: number
}