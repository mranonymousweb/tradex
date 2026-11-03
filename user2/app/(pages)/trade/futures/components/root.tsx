'use client';

import { useEffect, useMemo, useState } from 'react';
import { handleDecimal } from '@/helper/decimalPipe';
import { futuresBalance } from '@/helper/futures/balance';
import {
    futuresCurrencies,
    getOrderBook,
} from '@/helper/futures/currencyPrice';
import { getData } from '@/lib/storage';
import { FuturesContext, TotalObject } from '@/store/futures';
import { TcurrencyFavoriteSpot, TradePair } from '@/types/spot';
import { Container } from '@/components/common/container';
import { FooterMobile } from './footer-mobile';
import { TableOrders } from './Table';
import { Content } from './content';

export const FUTURES_PAIR_KEY = 'futures-pair';
const DEFAULT_PAIR = 'BTC_USDT';

function getStoredPair() {
    const stored = getData(FUTURES_PAIR_KEY);
    return typeof stored === 'string' && stored ? stored : DEFAULT_PAIR;
}

export default function RootPage({
    userId,
    tradePair,
    listFavorite,
}: {
    userId: number;
    tradePair: TradePair[] | undefined;
    listFavorite: TcurrencyFavoriteSpot[] | undefined;
}) {
    const [pair, setpair] = useState(getStoredPair);
    const [totalOrders, setTotalOrders] = useState<TotalObject>(null);
    const { balance } = futuresBalance('USDT');
    const { orders, isOrdersLoading } = getOrderBook(pair);
    const { prices, config, isReady, isLoading, indexPrice } = futuresCurrencies(pair);

    useEffect(() => {
        document.title = `${handleDecimal(orders?.last || prices?.price)} | ${pair}`;
    }, [orders, prices, pair]);

    const contextValue = useMemo(() => ({
        pair,
        setpair,
        prices,
        indexPrice,
        isReady,
        config,
        balance,
        userId,
        tradePair,
        listFavorite,
        isLoading,
        orders,
        isOrdersLoading,
        totalOrders,
        setTotalOrders,
    }), [
        pair,
        setpair,
        prices,
        isReady,
        config,
        balance,
        userId,
        tradePair,
        listFavorite,
        isLoading,
        orders,
        isOrdersLoading,
        totalOrders,
        setTotalOrders,
    ]);

    return (
        <FuturesContext value={contextValue}>
            <Container className="bg-secondary !max-w-full h-full pr-0 lg:!pr-1 !pl-0 md:pt-1">
                <Content />
                <TableOrders />
                <FooterMobile />
            </Container>
        </FuturesContext>
    );
}
