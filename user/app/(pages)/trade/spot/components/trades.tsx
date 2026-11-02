import { decimalPipe, handleDecimal } from '@/helper/decimalPipe';
import { time } from '@/helper/formatDate';
import { useTranslations } from 'next-intl';
import { NoData } from '@/components/ui/no-data';
import { useContext } from 'react';
import { MainSpotContext } from '@/store/trade-spot';
import { DotsLoader } from '@/components/ui/dots-loader';

interface OrderType {
    id: number;
    price: string;
    amount: string;
    timestamp: string;
    side: number;
}

interface TradesProps {
    orders: OrderType[];
    symbol: string;
    basePair: string;
}

export const Trades = ({ orders, symbol, basePair }: TradesProps) => {
    const t = useTranslations('spot');
    const { isOrdersLoading } = useContext(MainSpotContext);

    return (
        <div
            className="flex flex-col flex-1 bg-card md:mt-1 md:rounded-lg shadow-xs"
            dir="ltr"
        >
            <div className="border-b-1 px-4 py-2 font-medium text-sm hidden xl:block">
                <h2>{t('trades')}</h2>
            </div>
            <div className="flex flex-col px-3 pb-3">
                <div className="flex items-cener justify-between text-xs text-muted-foreground mt-3 mb-2">
                    <span>
                        {t('price')} ({basePair})
                    </span>
                    <span className="ltr:pr-3 rtl:pr-9">
                        {t('amount')} ({symbol})
                    </span>
                    <span>{t('time')}</span>
                </div>

                <ul className={orders?.length > 6 ? 'h-[15rem]' : 'h-[9rem]'}>
                    {
                        isOrdersLoading ? (
                            <div className="flex items-center justify-center h-full">
                                <DotsLoader />
                            </div>
                        ) :
                            (!orders || orders?.length === 0) ? (
                                <div className="flex items-center justify-center h-full">
                                    <NoData />
                                </div>
                            ) :
                                orders?.map((order: OrderType, index: number) => (
                                    <li
                                        key={index}
                                        className="flex items-center justify-between text-xs py-1"
                                    >
                                        <span
                                            className={`min-w-20 text-start ${order.side === 1 ? 'text-green' : 'text-destructive'}`}
                                        >
                                            {handleDecimal(order.price, basePair)}
                                        </span>
                                        <span className="min-w-12 text-end">
                                            {decimalPipe(order.amount, 6)}
                                        </span>
                                        <span className="min-w-20 text-end">
                                            {time(order.timestamp)}
                                        </span>
                                    </li>

                                ))
                    }
                </ul>
            </div>
        </div>
    );
};
