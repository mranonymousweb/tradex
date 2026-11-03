/* eslint-disable @typescript-eslint/no-explicit-any */
import { createContext, Dispatch, SetStateAction } from 'react';
import { Config, SwapCurrency } from '@/types/swap';

export type TypesValue = {
  setAllCurrencies: React.Dispatch<React.SetStateAction<any[]>>;
  currencies: SwapCurrency[];
  config: Config;
  inputPayment: string;
  inputReceive: string;
  setInputPayment: Dispatch<SetStateAction<any>>;
  setInputReceive: Dispatch<SetStateAction<any>>;
  selectedPayment: SwapCurrency;
  setSelectedPayment: Dispatch<SetStateAction<SwapCurrency | any>>;
  selectedReceive: SwapCurrency;
  setSelectedReceive: Dispatch<SetStateAction<SwapCurrency | any>>;
  changeInput: string;
  setChangeInput: Dispatch<SetStateAction<any>>;
  decimaPay: number;
  decimaReceive: number;
  decimaCurrency: number;
  decimaFee: number;
  decimaFinal: number;
};

export const SwapContext = createContext<TypesValue>({} as TypesValue);
