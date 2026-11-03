import { Currency } from '@/types/wallet';

export const findCurrency = (currencies: Currency[], selectedCoin: number) => {
  return currencies?.find((c) => c.id === Number(selectedCoin));
};
