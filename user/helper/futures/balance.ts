'use client';

import { useQuery } from '@tanstack/react-query';
import { userAssets } from '@/services/wallet.services';

// TODO: update balance with socket;

export const futuresBalance = (symbol: string) => {
  const { data } = useQuery({
    queryKey: ['assets_futures', symbol],
    queryFn: () => userAssets(symbol),
    //refetchInterval: 2000,
  });
  const userBalance = data?.[0]?.feature_balance;
  return { balance: userBalance ?? 0 };
};
