import { handleDecimal } from '@/helper/decimalPipe';

interface OrderType {
  last: number;
  side: 1 | 2;
}

export const MarketPrice = ({
  orders,
  basePair,
}: {
  orders: OrderType;
  basePair: string;
}) => {
  const lastOrder = orders ? orders.last : 0;
  const lastSide = orders?.side;

  return (
    <div className="flex items-center py-2 pl-3 lg:pl-0 min-w-24">
      <span
        className={`text-lg font-medium ${lastSide === 1 ? 'text-green' : 'text-destructive'}`}
      >
        {handleDecimal(String(lastOrder), basePair)}
      </span>
    </div>
  );
};
