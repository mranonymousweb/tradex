import { Order } from '@/types/spot';

export const matchOrder = (orders: Order[], updateOrder: Order) => {
  const newOrders = orders?.map((order) => {
    if (order.id === updateOrder?.id) {
      return updateOrder;
    } else {
      return order;
    }
  });
  return newOrders;
};
