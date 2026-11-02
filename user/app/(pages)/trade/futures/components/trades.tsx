import { DotsLoader } from '@/components/ui/dots-loader';
import { NoData } from '@/components/ui/no-data';
import { handleDecimal } from '@/helper/decimalPipe';
import { time } from '@/helper/formatDate';
import { FuturesContext } from '@/store/futures';
import { useContext } from 'react';

interface OrderType {
    price: string;
    amount: string;
    total: number;
    side: number;
    opened_at: string;
}

interface TradesProps {
    symbol: string;
}

export const Trades = ({ symbol }: TradesProps) => {
    const { orders, isOrdersLoading } = useContext(FuturesContext);
    const closeOrder = orders ? orders.done : null;
    const closeOrderLimit = closeOrder?.slice(0, 6);

    return (
        <div
            className="flex flex-col flex-1 bg-background md:mt-1 md:rounded-lg shadow-xs"
            dir="ltr"
        >
            <div className="border-b-1 px-4 py-2 font-medium text-sm hidden xl:block">
                <h2>Trades</h2>
            </div>
            <div className="flex flex-col px-3 pb-3">
                <div className="flex items-cener justify-between text-xs text-muted-foreground mt-3 mb-2">
                    <span>Price (USDT)</span>
                    <span>Amount ({symbol})</span>
                    <span>Time ({symbol})</span>
                </div>

                <ul className="h-[9rem]">
                    {isOrdersLoading ? (
                        <div className="flex items-center justify-center h-[9rem]">
                            <DotsLoader />
                        </div>
                    ) : (!closeOrderLimit || closeOrderLimit.length === 0) ? (
                        <div className="flex items-center justify-center h-[9rem]">
                            <NoData />
                        </div>
                    ) : (
                        closeOrderLimit?.map((order: OrderType, i: number) => (
                            <li
                                key={i}
                                className="flex items-center justify-between text-xs py-1"
                            >
                                <span
                                    className={`min-w-20 text-start ${order.side === 1 ? 'text-green' : 'text-destructive'}`}
                                >
                                    {handleDecimal(order.price)}
                                </span>
                                <span className="min-w-10 text-end">
                                    {handleDecimal(order.amount)}
                                </span>
                                <span className="min-w-20 text-end">{time(order.opened_at)}</span>
                            </li>
                        ))
                    )}
                </ul>
            </div>
        </div>
    );
};
