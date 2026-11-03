/* eslint-disable @typescript-eslint/no-explicit-any */
import { createContext } from 'react';
import {
  TcurrencyFavoriteSpot,
  TradePair,
  UserPackageLevel,
} from '@/types/spot';

export type TotalObject = {
  amount: number;
  total: number;
  price: number;
} | null;

export type FuturesType = {
  price: number;
  tradePair: TradePair[] | undefined;
  pair: string;
  balance: {
    main_balance: string;
    base_balance: string;
  };
  orders: any;
  isOrdersLoading: boolean;
  userId: number | undefined;
  userPackage: UserPackageLevel | undefined;
  listFavorite: TcurrencyFavoriteSpot[] | undefined;
  isFiat: boolean;
  USD_IRR: number | undefined;
  totalOrders: TotalObject | null;
  setTotalOrders: (totalOrders: TotalObject) => void;
};

export const MainSpotContext = createContext<FuturesType>({} as FuturesType);
export const ActionFormContext = createContext<any>({} as any);
