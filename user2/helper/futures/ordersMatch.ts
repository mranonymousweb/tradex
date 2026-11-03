import { Position } from '@/types/futures';

export const matchOrder = (orders: Position[], updateOrder: Position) => {
    const newOrders = orders?.map((order) => {
        if (order.id === updateOrder?.id) {
            return {
                ...order,
                pnl: updateOrder?.pnl,
                roi: updateOrder?.roi,
                market_price: updateOrder?.market_price,
                status: updateOrder?.status,
            };
        } else {
            return order;
        }
    });
    return newOrders;
};
