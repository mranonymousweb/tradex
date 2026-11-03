import { useEffect, useMemo, useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { MarketCurrency } from '@/types/market-currency';
import { getCurrencies } from '@/services/markets';
import { usdPrice } from '../spot/irtPrice';
import { getSocket } from '../spot/socket';

//const useSocket = () => {
//    useEffect(() => {
//        const onConnect = () => {
//            console.log("socket connected", socket.connected);
//        };
//        socket.on("connect", onConnect);
//        return () => {
//            socket.off("connect", onConnect);
//        };
//    }, []);
//}

export const usePairPrice = (pairs: string[] | undefined) => {
  const [socketData, setSocketData] = useState<Record<string, any>>({});

  useEffect(() => {
    if (!pairs?.length) return;

    const socket = getSocket();
    const emit = 'pair:price';

    const handler = (payload: any) => {
      const price = payload.data;
      if (!price?.symbol) return;
      setSocketData((prev) => ({ ...prev, [price.symbol]: price }));
    };

    socket.emit(`subscribe:${emit}`, { pair: pairs });
    socket.on(emit, handler);

    return () => {
      socket.emit(`unsubscribe:${emit}`, { pair: pairs });
      socket.off(emit, handler);
    };
  }, [JSON.stringify(pairs)]);

  return socketData;
};

export const marketsCurrencies = (path?: string) => {
  const { data, isPending } = useQuery({
    queryKey: ['markets-currency', path],
    queryFn: () => getCurrencies(path),
  });

  const currencies = data?.data;
  const USD_PRICE = usdPrice();

  const pairs = useMemo(
    () => currencies?.map((c: any) => `${c.symbol}_USDT`),
    [currencies],
  );
  const socketData = usePairPrice(pairs);

  const mergedCurrencies = useMemo(() => {
    if (!currencies) return;

    return currencies
      .filter((cur: MarketCurrency) => cur?.symbol !== 'IRT')
      .map((currency: any) => {
        const pair = `${currency?.symbol}_USDT`;
        const live = socketData?.[pair];

        return {
          ...currency,
          last: live?.last ?? currency.priceData.last,
          changePercent:
            live?.changePercent ?? currency.priceData.changePercent,
          high24h: live?.high24h ?? currency.priceData.high24h,
          low24h: live?.low24h ?? currency.priceData.low24h,
          quoteVolume: live?.quoteVolume ?? currency.priceData.quoteVolume,
          volume: live?.volume ?? currency.priceData.volume,
        };
      });
  }, [currencies, socketData]);

  return {
    currencies: mergedCurrencies,
    isPending,
    data,
    USD_PRICE,
  };
};

export const mergeCurrency = (currency: any, live: any) => ({
  ...currency,
  last: live?.last ?? currency.priceData.last,
  changePercent: live?.changePercent ?? currency.priceData.changePercent,
  high24h: live?.high24h ?? currency.priceData.high24h,
  low24h: live?.low24h ?? currency.priceData.low24h,
  quoteVolume: live?.quoteVolume ?? currency.priceData.quoteVolume,
  volume: live?.volume ?? currency.priceData.volume,
});

export const dashboardCurrencies = () => {
  const { data, isPending } = useQuery({
    queryKey: ['markets-currency'],
    queryFn: () => getCurrencies(),
  });

  const currencies = useMemo(
    () =>
      data?.data
        .filter((c: MarketCurrency) => !['USDT', 'IRT'].includes(c?.symbol))
        .slice(0, 4),
    [data],
  );

  const pairs = useMemo(
    () => currencies?.map((c: any) => `${c.symbol}_USDT`),
    [currencies],
  );

  const socketData = usePairPrice(pairs);

  const mergedCurrencies = useMemo(() => {
    if (!currencies) return;
    return currencies.map((currency: any) =>
      mergeCurrency(currency, socketData[`${currency.symbol}_USDT`]),
    );
  }, [currencies, socketData]);

  return { currencies: mergedCurrencies, isPending };
};
