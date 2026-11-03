import React from 'react';
import { Plugins } from '@/types';
import { PackageType } from '@/types/profile';
import { Currency } from '@/types/wallet';

export type TypesValue = {
  selectedCoin: string;
  setSelectedCoin: React.Dispatch<React.SetStateAction<any>>;
  currencies: Currency[];
  setAllCurrencies: (currencies: Currency[]) => void;
  packageInfo: PackageType;
  googleAuth?: boolean;
  search: string;
  setSearch: (s: string) => void;
  plugins?: Plugins;
};
export const WalletContext = React.createContext<TypesValue>({} as TypesValue);
