import { decimalPipe, handleDecimal } from '@/helper/decimalPipe';
import { cn } from '@/lib/utils';
import { FuturesContext } from '@/store/futures';
import { useContext } from 'react';

export const MarketPrice = () => {
    const { orders, isOrdersLoading, prices } = useContext(FuturesContext);
    const price = isOrdersLoading ? undefined : orders?.last;
    const side = orders?.side;
    const sign = Math.sign(prices?.changePercent);;
    const color = sign === 1 ? 'text-green' : 'text-destructive';

    return (
        <div className="flex flex-col py-2 pl-3 lg:pl-0 min-w-24">
            <span
                className={`text-lg font-medium ${side !== 1 ? 'text-destructive' : 'text-green'}`}
            >
                {handleDecimal(price)}
            </span>
            <span
                className={cn(
                    "text-xs",
                    color
                )}
            >
                {decimalPipe(prices?.changePercent, 2)}%
            </span>
        </div>
    );
};
