/* eslint-disable @typescript-eslint/no-explicit-any */
import { useEffect, useMemo, useState } from 'react';
import { useInfiniteQuery } from '@tanstack/react-query';
import { SwapCurrency } from '@/types/swap';
import { getSwapCurrency } from '@/services/swap.services';
import { getSocket } from '../spot/socket';

const socket = getSocket();

// ─── shared hook ────────────────────────────────────────────────────────────

const useSwapSocketData = (pairs: string[]) => {
    const [socketData, setSocketData] = useState<Record<string, any>>({});

    useEffect(() => {
        if (!pairs.length) return;

        const pairEmit = 'pair:price';
        const fiatEmit = 'fiat:USDT_IRT';

        const pairHandler = (payload: any) => {
            const price = payload.data;
            if (!price?.symbol) return;
            setSocketData((prev) => ({ ...prev, [price.symbol]: price }));
        };

        const fiatHandler = (payload: any) => {
            setSocketData((prev) => ({
                ...prev,
                IRT_USDT:
                {
                    ...payload.data,
                    last: 1 / payload.data?.last,
                    buy_last: payload.data?.buy_last,
                    sell_last: payload.data?.last
                },
            }));
        };

        socket.emit(`subscribe:${pairEmit}`, { pair: pairs });
        socket.emit(`subscribe:${fiatEmit}`, { pair: 'USDT_IRT' });
        socket.on(pairEmit, pairHandler);
        socket.on(fiatEmit, fiatHandler);

        return () => {
            socket.emit(`unsubscribe:${pairEmit}`, { pair: pairs });
            socket.emit(`unsubscribe:${fiatEmit}`, { pair: 'USDT_IRT' });
            socket.off(pairEmit, pairHandler);
            socket.off(fiatEmit, fiatHandler);
        };
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [JSON.stringify(pairs)]);

    return socketData;
};

// ─── shared merge ────────────────────────────────────────────────────────────

const mergeCurrency = (currency: any, live: any) => ({
    currency,
    symbol: currency?.symbol,
    last: live?.last ?? currency?.price,
    buy_last: live?.buy_last ?? currency?.buy_last,
    sell_last: live?.sell_last ?? currency?.sell_last,
    high24h: live?.high24h ?? currency?.high24h,
    low24h: live?.low24h ?? currency?.low24h,
    changePercent: live?.changePercent ?? currency?.changePercent,
});

// ─── hooks ───────────────────────────────────────────────────────────────────

export const swapCurrencies = (allCurrencies: SwapCurrency[]) => {
    const pairs = useMemo(
        () => allCurrencies.map((c) => `${c.symbol}_USDT`),
        [allCurrencies],
    );

    const socketData = useSwapSocketData(pairs);

    const currencies = useMemo(
        () =>
            allCurrencies.map((c) =>
                mergeCurrency(c, socketData[`${c.symbol}_USDT`]),
            ),
        [allCurrencies, socketData],
    );

    return { currencies };
};

export const swapListCurrency = (filters?: string) => {
    const { data, isPending, fetchNextPage, hasNextPage, isFetchingNextPage } =
        useInfiniteQuery({
            queryKey: ['swap-currency', filters],
            queryFn: ({ pageParam = 1 }) => getSwapCurrency(pageParam, filters),
            initialPageParam: 1,
            getNextPageParam: (lastPage, pages) => {
                const lastPageNum =
                    lastPage?.currencies?.original?.meta?.last_page ?? 1;
                return pages.length < lastPageNum ? pages.length + 1 : undefined;
            },
        });

    const currencies = useMemo(
        () =>
            data?.pages.flatMap((page) => page?.currencies?.original?.data ?? []) ??
            [],
        [data],
    );

    const pairs = useMemo(
        () => currencies.map((c: any) => `${c.symbol}_USDT`),
        [currencies],
    );

    const socketData = useSwapSocketData(pairs);

    const mergedCurrencies = useMemo(
        () =>
            currencies.map((c: any) =>
                mergeCurrency(c, socketData[`${c.symbol}_USDT`]),
            ),
        [currencies, socketData],
    );

    return {
        currencies: mergedCurrencies,
        isLoading: isPending,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
    };
};
