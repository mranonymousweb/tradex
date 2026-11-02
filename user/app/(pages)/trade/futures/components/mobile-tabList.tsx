import { useContext, useState } from 'react';
import { FuturesContext } from '@/store/futures';
import { OrderBooks } from './order-books';
import { Trades } from './trades';
import TradingViewChart from './TradingViewChart';

export const MobileTabList = () => {
    const { pair } = useContext(FuturesContext);
    const [tabList, setTabList] = useState('chart');
    const symbol = pair.split('_')[0];

    return (
        <>
            <div
                className="flex items-center bg-background mt-1 border-b p-2 pb-0 space-x-3 text-muted-foreground"
                dir="ltr"
            >
                <span
                    className={`pb-2 ${tabList === 'chart' && 'active_tab'}`}
                    onClick={() => setTabList('chart')}
                >
                    chart
                </span>
                <span
                    className={`pb-2 ${tabList === 'orders' && 'active_tab'}`}
                    onClick={() => setTabList('orders')}
                >
                    Order Book
                </span>
                <span
                    className={`pb-2 ${tabList === 'trades' && 'active_tab'}`}
                    onClick={() => setTabList('trades')}
                >
                    Trades
                </span>
            </div>

            {tabList === 'chart' && (
                <div className="h-[500px] w-full">
                    <TradingViewChart />
                </div>
            )}
            {tabList === 'orders' && <OrderBooks />}
            {tabList === 'trades' && <Trades symbol={symbol} />}
        </>
    );
};
