import React from 'react';
import { getCompact } from '@/helper/compact';
import { getOpenOrders } from '@/helper/futures/currencyPrice';
import { matchOrder } from '@/helper/futures/ordersMatch';
import { FuturesContext } from '@/store/futures';
import { useQuery } from '@tanstack/react-query';
import { ordersMarketFutures } from '@/services/futures.services';
import { OrderHistory } from './order-history';
import { OpneOrders } from './table-open-orders';
import { PendingOrders } from './table-pending-orders';

enum Tab {
    OPEN = 'OPEN',
    PENDING = 'PENDING',
    HISTORY = 'HISTORY',
}

enum OrderStatus {
    PENDING = 1,
    OPEN = 2,
    CLOSED = 3,
}

export const TableOrders = () => {
    const { pair, userId } = React.useContext(FuturesContext);
    const updateOrder = getOpenOrders(pair, userId);
    const [tabActive, setTabActive] = React.useState<Tab>(Tab.OPEN);

    const { data } = useQuery({
        queryKey: ['futures-orders', pair],
        queryFn: () => ordersMarketFutures(pair),
    });

    const orders = matchOrder(data?.data, updateOrder?.order);

    const openOrder = orders?.find((order) => order.status === OrderStatus.OPEN);
    const pendingOrder = orders?.filter(
        (order) => order.status === OrderStatus.PENDING,
    );

    const handleActiveClass = (tab: Tab): string => {
        return tabActive === tab
            ? 'pb-3 text-foreground active_tab'
            : 'text-muted-foreground pb-3';
    };

    return (
        <div className="bg-background h-full md:h-auto rounded-lg my-1">
            <div className="flex items-center space-x-3 border-b p-3 pb-0 text-sm font-medium" dir='ltr'>
                <button
                    type="button"
                    className={handleActiveClass(Tab.OPEN)}
                    onClick={() => setTabActive(Tab.OPEN)}
                >
                    Open Orders({openOrder ? '1' : '0'})
                </button>
                <button
                    type="button"
                    className={handleActiveClass(Tab.PENDING)}
                    onClick={() => setTabActive(Tab.PENDING)}
                >
                    Pending Order({pendingOrder ? getCompact(pendingOrder.length) : '0'})
                </button>
                <button
                    type="button"
                    className={handleActiveClass(Tab.HISTORY)}
                    onClick={() => setTabActive(Tab.HISTORY)}
                >
                    Order History
                </button>
            </div>

            {/* {!isPending && <ContentLoader />} */}
            {tabActive === Tab.OPEN && <OpneOrders items={openOrder} />}
            {tabActive === Tab.PENDING && <PendingOrders items={pendingOrder} />}
            {tabActive === Tab.HISTORY && <OrderHistory />}
        </div>
    );
};
