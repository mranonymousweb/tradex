export interface IOrder {
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

    raw: {
        mainCurrency?: string | null;
        baseCurrency?: string | null;
        leverage: number;
        avgPrice: string;
        total: string;
        stopPrice: string;
        filledReceiveAmount: string;
        filledTotal: string;
        fee: string;
        feePercent: string;
        status: number;
        createdAt: string;
        canceledAt: string;
        deletedAt: string;
        doneAt: string;
        openAt: string;
        updatedAt: string;
    };

    timestamp: number;
}