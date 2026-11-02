import { useEffect, useMemo, useRef, useState } from 'react';
import { useInfiniteQuery } from '@tanstack/react-query';
import { TcurrencyFavoriteSpot } from '@/types/spot';
import { getCurrencyBase } from '@/services/spot.services';
import { getSocket } from './socket';

const FLUSH_INTERVAL = 10_000;

export const currenciesPrice = (
  pair: string = 'BTC_USDT',
  base: string = 'USDT',
  search: string = '',
  listFavorite: TcurrencyFavoriteSpot[] = [],
) => {
  const socket = getSocket();
  const bufferRef = useRef<Record<string, any>>({});
  const [socketData, setSocketData] = useState<Record<string, any>>({});

  const { data, isPending, fetchNextPage, hasNextPage, isFetchingNextPage } =
    useInfiniteQuery({
      queryKey: ['currencyBase', base, search],
      queryFn: ({ pageParam = 1 }) => getCurrencyBase(pageParam, base, search),
      initialPageParam: 1,
      getNextPageParam: (pageParam) => {
        const lastPage = pageParam?.meta?.last_page ?? 1;
        return pageParam?.meta?.current_page < lastPage
          ? pageParam?.meta?.current_page + 1
          : undefined;
      },
    });

  const currencies = useMemo(
    () => data?.pages.flatMap((page) => page?.data) ?? [],
    [data],
  );

  const pairs = useMemo(() => {
    const currencyPairs = currencies.map((c) => c.pairs);
    return currencyPairs.includes(pair)
      ? currencyPairs
      : [...currencyPairs, pair];
  }, [currencies, pair]);

  // socket subscription
  useEffect(() => {
    if (!pairs.length) return;
    const emit = 'pair:price';

    const handler = (payload: any) => {
      const price = payload.data;
      if (!price?.symbol) return;
      bufferRef.current[price.symbol] = price;
    };

    socket.emit(`subscribe:${emit}`, { pair: pairs });
    socket.on(emit, handler);

    return () => {
      socket.emit(`unsubscribe:${emit}`, { pair: pairs });
      socket.off(emit, handler);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [JSON.stringify(pairs)]);

  // flush buffer to state
  useEffect(() => {
    const interval = setInterval(() => {
      setSocketData({ ...bufferRef.current });
    }, FLUSH_INTERVAL);
    return () => clearInterval(interval);
  }, []);

  const favoriteSet = useMemo(
    () =>
      new Set(
        listFavorite?.map(
          (f) => `${f.mainCurrency?.symbol}_${f.baseCurrency?.symbol}`,
        ),
      ),
    [listFavorite],
  );

  const mergedCurrencies = useMemo(
    () =>
      currencies.map((currency: any) => {
        if (!currency?.pairs) return currency;
        const live = socketData[currency.pairs];
        return {
          ...currency,
          price: live?.last ?? currency.price,
          changePercent: live?.changePercent ?? currency.changePercent,
          favorite: favoriteSet.has(currency.pairs),
        };
      }),
    [currencies, socketData, favoriteSet],
  );

  const findCurrency = useMemo(
    () => mergedCurrencies.find((c) => c.pairs === pair),
    [mergedCurrencies, pair],
  );

  return {
    price: findCurrency?.price ?? 0,
    prices: findCurrency,
    currencies: mergedCurrencies,
    isLoading: isPending,
    fetchNextPage,
    hasNextPage,
    isFetchingNextPage,
  };
};
