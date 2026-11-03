'use client';

import { useEffect, useState } from 'react';
import dynamic from 'next/dynamic';
import { handleDecimal } from '@/helper/decimalPipe';
import { currenciesPrice } from '@/helper/spot/currencyPrice';
import { usdtIrtPrice } from '@/helper/spot/irtPrice';
import { getOrderBook } from '@/helper/spot/orders';
import { MainSpotContext, TotalObject } from '@/store/trade-spot';
import { useQuery } from '@tanstack/react-query';
import {
    TcurrencyFavoriteSpot,
    TradePair,
    UserPackageLevel,
} from '@/types/spot';
import { useIsTablet } from '@/hooks/use-tablet';
import { spotBalance } from '@/services/spot.services';
import { Container } from '@/components/common/container';
import { DialogMarketBase } from './dialog-marketBase';
import { FooterMobile } from './footer-mobile';
import FormCreateSpot from './form';
import { MarketPrice } from './market-price';
import { MobileTabList } from './mobile-tabList';
import { OrderBooks } from './order-books';
import { PriceChanges } from './price-changes';
import { TableOrders } from './Table';
import { TopCurrency } from './top-currency';

const TradingViewChart = dynamic(() => import('./TradingViewChart'), {
    ssr: false,
    loading: () => (
        <div className="h-full w-full animate-pulse rounded-lg bg-muted" />
    ),
});

export default function RootPage({
    pair,
    userId,
    userPackage,
    tradePair,
    listFavorite,
}: {
    pair: string;
    userId: number | undefined;
    userPackage: UserPackageLevel | undefined;
    tradePair: TradePair[] | undefined;
    listFavorite: TcurrencyFavoriteSpot[] | undefined;
}) {
    const isTablet = useIsTablet();
    const [buttomSheet, setButtomSheet] = useState(false);
    const [totalOrders, setTotalOrders] = useState<TotalObject | null>(null);
    const [switchTab, setSwitchTab] = useState<boolean>(false); //false => Buy & true => Sell

    const basePair = pair.split('_')[1];

    const { orders, isOrdersLoading } = getOrderBook(pair);
    const { price } = currenciesPrice(pair, basePair);

    const isFiat =
        tradePair?.find((p) => p.baseCurrency.symbol === basePair)?.baseCurrency
            .type === 2;
    const USD_IRR = usdtIrtPrice(isFiat);

    useEffect(() => {
        document.title = `${handleDecimal(orders?.last ?? price)} | ${pair}`;
    }, [orders, pair, price]);

    const { data: balance, isPending: isBalanceLoading } = useQuery({
        queryKey: ['spot-balance', pair],
        queryFn: () => spotBalance(pair),
        //enabled: Boolean(userId),
    });

    return (
        <MainSpotContext
            value={{
                price,
                tradePair,
                pair,
                balance,
                orders,
                isOrdersLoading,
                userId,
                userPackage,
                listFavorite,
                isFiat,
                USD_IRR,
                totalOrders,
                setTotalOrders,
            }}
        >
            <Container className="bg-secondary !max-w-full h-full pr-0 lg:!pr-1 !pl-0 md:pt-1">
                <div className="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-x-1">
                    <FormCreateSpot
                        buttomSheet={buttomSheet}
                        switchTab={switchTab}
                        setSwitchTab={setSwitchTab}
                        isBalanceLoading={isBalanceLoading}
                    />

                    <div className="flex flex-col-reverse md:col-span-2 xl:col-span-3 2xl:col-span-4 xl:flex-row xl:space-x-1">
                        <div className="hidden md:flex xl:flex-col h-full gap-x-1 w-full xl:w-4/12 2xl:w-4/12">
                            <OrderBooks />
                        </div>

                        <div className="flex flex-col w-full">
                            <TopCurrency />

                            <div
                                className="flex xl:flex-wrap relative items-center bg-card md:rounded-r-lg lg:mt-1"
                                dir="ltr"
                            >
                                <div className="flex flex-col md:flex-row md:items-center lg:space-x-2">
                                    <DialogMarketBase />
                                    <MarketPrice
                                        orders={orders}
                                        basePair={basePair}
                                    />
                                </div>
                                <PriceChanges />
                            </div>

                            {/* Mobile tabs */}
                            {isTablet && <MobileTabList />}

                            <div className="hidden md:block h-[400px] xl:h-full w-full mt-1 bg-background">
                                <TradingViewChart />
                            </div>
                        </div>
                    </div>
                </div>
                <TableOrders />
                <FooterMobile
                    setButtomSheet={setButtomSheet}
                    buttomSheet={buttomSheet}
                    setSwitchTab={setSwitchTab}
                />
            </Container>
        </MainSpotContext>
    );
}
