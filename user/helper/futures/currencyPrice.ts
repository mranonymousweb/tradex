import { useEffect, useMemo, useRef, useState } from 'react';
import { useInfiniteQuery, useQuery } from '@tanstack/react-query';
import { MarketConfig } from '@/types/futures';
import { TcurrencyFavoriteSpot } from '@/types/spot';
import { getCurrencyList, getOptions } from '@/services/futures.services';
import { getSocket } from './socket';

export const futuresCurrencies = (
    pairOrBase: string = 'BTC_USDT',
    search: string = '',
    listFavorite: TcurrencyFavoriteSpot[] = [],
) => {
    const [socketData, setSocketData] = useState<any>();

    const isFullPair = pairOrBase.includes('_');
    const activePair = isFullPair ? pairOrBase : undefined;
    const baseCurrency = isFullPair
        ? pairOrBase.split('_')[1] ?? 'USDT'
        : pairOrBase || 'USDT';

    const { data, isPending, fetchNextPage, hasNextPage, isFetchingNextPage } =
        useInfiniteQuery({
            queryKey: ['currencyBase', baseCurrency, search],
            queryFn: ({ pageParam = 1 }) =>
                getCurrencyList(pageParam, baseCurrency, search),
            initialPageParam: 1,
            getNextPageParam: (pageParam) => {
                const last_Page = pageParam?.meta?.last_page ?? 1;
                return pageParam?.meta?.current_page < last_Page
                    ? pageParam?.meta?.current_page + 1
                    : undefined;
            },
        });

    const { data: config, isPending: isPendingConfig } = useQuery<MarketConfig>({
        queryKey: ['config'],
        queryFn: () => getOptions(),
    });

    const currencies = useMemo(() => {
        return data?.pages.flatMap((page) => page?.data) ?? [];
    }, [data]);

    const pairs = useMemo(() => {
        const currencyPairs = currencies.map((c) => c.pairs);

        if (!activePair || currencyPairs.includes(activePair)) {
            return currencyPairs;
        }

        return [...currencyPairs, activePair];
    }, [currencies, activePair]);

    useEffect(() => {
        if (!pairs.length) return;
        const socket = getSocket();
        const emit = 'future:pair:price';

        const handler = (payload: any) => {
            const price = payload.data;
            if (!price?.symbol) return;

            setSocketData((prev: any) => ({
                ...prev,
                [price.symbol]: price,
            }));
        };

        socket.emit(`subscribe:${emit}`, { pair: pairs });
        socket.on(emit, handler);

        return () => {
            socket.emit(`unsubscribe:${emit}`, { pair: pairs });
            socket.off(emit, handler);
        };
    }, [pairs]);

    const mergedCurrencies = useMemo(() => {
        const favoriteSet = new Set(
            listFavorite?.map((f) => f.mainCurrency.symbol),
        );

        return currencies.map((currency: any) => {
            if (!currency?.pairs) return currency;
            const live = socketData?.[currency?.pairs];

            return {
                ...currency,
                price: live?.price ?? currency.price,
                changePercent: live?.changePercent ?? currency.changePercent,
                favorite: favoriteSet.has(currency.mainCurrency.symbol),
            };
        });
    }, [currencies, socketData, listFavorite]);

    const findCurrency = useMemo(
        () =>
            activePair
                ? mergedCurrencies.find((c) => c.pairs === activePair)
                : undefined,
        [mergedCurrencies, activePair],
    );

    //const prices = activePair
    //    ? socketData?.[activePair] ?? findCurrency
    //    : undefined;
    const prices = activePair ? findCurrency : undefined;
    const isLoading = isPendingConfig || isPending;
    const indexPrice = activePair ? socketData?.[activePair]?.indexPrice : undefined;

    return {
        prices,
        config,
        isReady: !currencies,
        currencies: mergedCurrencies,
        findCurrency,
        isLoading,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        indexPrice
    };
};

// Get Order Book Futures
export const getOrderBook = (pair = 'BTC_USDT') => {
    const [data, setData] = useState<any>(null);
    const [isLoading, setIsLoading] = useState(true);
    const activePairRef = useRef(pair);

    useEffect(() => {
        if (!pair) return;

        activePairRef.current = pair;
        const subscribedPair = pair;
        let cancelled = false;

        setIsLoading(true);
        setData(null);

        const socket = getSocket();
        const event = 'future:all:orders';

        const timeout = setTimeout(() => {
            if (!cancelled) setIsLoading(false);
        }, 5000);

        const handler = (payload: any) => {
            if (cancelled || activePairRef.current !== subscribedPair) return;
            clearTimeout(timeout);
            setData(payload.data);
            setIsLoading(false);
        };

        socket.on(event, handler);
        socket.emit('subscribe:future:all:orders', { pair: subscribedPair });

        return () => {
            cancelled = true;
            clearTimeout(timeout);
            socket.off(event, handler);
            socket.emit('unsubscribe:future:all:orders', {
                pair: subscribedPair,
            });
        };
    }, [pair]);

    return { orders: data, isOrdersLoading: isLoading };
};

// Get Open Orders Futures
export const getOpenOrders = (pair = 'BTC_USDT', userId: number) => {
    const [data, setData] = useState<any>(null);

    useEffect(() => {
        if (!pair) return;
        const socket = getSocket();

        const handler = (payload: any) => {
            setData(payload.data);
        };

        socket.on('future:my:orders', handler);
        socket.emit('subscribe:future:my:orders', { pair: `${userId}-${pair}` });

        return () => {
            socket.off('future:my:orders', handler);
            socket.emit('unsubscribe:future:my:orders', {
                pair: `${userId}-${pair}`,
            });
        };
    }, [pair, userId]);
    return data;
};
