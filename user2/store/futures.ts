/* eslint-disable @typescript-eslint/no-explicit-any */
import { createContext } from 'react';
import { MarketConfig, TcurrencyPrice } from '@/types/futures';
import { TcurrencyFavoriteSpot, TradePair } from '@/types/spot';

export type TotalObject = {
    amount: number;
    total: number;
    price: number;
} | null;

export type FuturesType = {
    prices: TcurrencyPrice;
    isReady: boolean | unknown;
    config: MarketConfig | any;
    pair: string;
    setpair: (pair: string) => void;
    balance: number;
    userId: number;
    tradePair: TradePair[] | undefined;
    listFavorite: TcurrencyFavoriteSpot[] | undefined;
    isLoading: boolean;
    orders: any;
    isOrdersLoading: boolean;
    totalOrders: TotalObject;
    setTotalOrders: (totalOrders: TotalObject) => void;
    indexPrice: number;
};

export const FuturesContext = createContext<FuturesType>({} as FuturesType);
export const ActionFormContext = createContext<any>({} as any);
