interface OrderType {
  id: number;
  enter_price: string;
  quantity: string;
  cost: number;
  side: number;
}
export const groupedOrders = (orders: OrderType[]) => {
  if (!orders) return;
  const grouped = orders.reduce(
    (acc, order) => {
      if (!acc[+order.enter_price]) {
        acc[+order.enter_price] = {
          side: Number(order.side),
          id: Number(order.id),
          enter_price: Number(order.enter_price),
          quantity: Number(order.quantity),
          cost: Number(order.cost),
        };
      } else {
        acc[+order.enter_price].quantity += Number(order.quantity);
        acc[+order.enter_price].cost += Number(order.cost);
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
      }
    >,
  );

  return Object.values(grouped);
};
