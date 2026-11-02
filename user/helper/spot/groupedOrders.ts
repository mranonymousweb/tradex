interface OrderType {
  _id: number;
  price: string;
  amount: string;
  total: number;
  side: number;
  filled_amount: number;
}
export const groupedOrders = (orders: OrderType[]) => {
  if (!orders) return;
  const grouped = orders.reduce(
    (acc, order) => {
      if (!acc[+order.price]) {
        acc[+order.price] = {
          side: Number(order.side),
          id: Number(order._id),
          enter_price: Number(order.price),
          quantity: Number(order.amount),
          cost: Number(order.total),
          filledAmount: Number(order.filled_amount),
        };
      } else {
        acc[+order.price].quantity += Number(order.amount);
        acc[+order.price].cost += Number(order.total);
        acc[+order.price].filledAmount += Number(order.filled_amount);
      }
      return acc;
    },
    {} as Record<
      number,
      {
        id: number;
        enter_price: number;
        quantity: number;
        cost: number;
        side: number;
        filledAmount: number;
      }
    >,
  );

  return Object.values(grouped);
};
