import { useEffect, useState } from 'react';
import { useInfiniteQuery } from '@tanstack/react-query';
import { Currency } from '@/types/wallet';
import { getWalletCurrencies } from '@/services/wallet.services';

export const useListCurrency = (search: string) => {
  const [allCurrencies, setAllCurrencies] = useState<Currency[] | []>([]);
  const normalizedSearch = search?.trim() || undefined;

  const { data, fetchNextPage, hasNextPage, isFetchingNextPage, isLoading } =
    useInfiniteQuery({
      queryKey: ['wallet-currency', search],
      queryFn: ({ pageParam = 1 }) => getWalletCurrencies(pageParam, search),
      initialPageParam: 1,
      getNextPageParam: (lastPage, pages) => {
        const last = lastPage?.meta?.last_page ?? 1;
        return pages.length < last ? pages.length + 1 : undefined;
      },

      select: (data) => ({
        pages: data.pages.flatMap((page) => page.data),
        pageParams: data.pageParams,
      }),
    });

  useEffect(() => {
    if (!data) return;

    setAllCurrencies((prev) => {
      const newItems = data.pages.filter(
        (item) => !prev.some((p: Currency) => p.symbol === item.symbol),
      );
      return [...prev, ...newItems];
    });
  }, [data]);

  return {
    currencies: allCurrencies,
    setAllCurrencies,
    fetchNextPage,
    hasNextPage,
    isFetchingNextPage,
    normalizedSearch,
    isLoading,
  };
};
