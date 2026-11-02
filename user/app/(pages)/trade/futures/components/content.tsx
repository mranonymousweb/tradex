'use client';

import dynamic from 'next/dynamic';
import { Tooltip, TooltipContent, TooltipTrigger } from "@/components/ui/tooltip";
import { useIsTablet } from "@/hooks/use-tablet";
import { Forms } from "./forms";
import { OrderBooks } from "./order-books";
import { TopCurrency } from "./top-currency";
import { DialogMarketBase } from "./dialog-marketBase";
import { TooltipProvider } from "@/components/ui/tooltip";
import { BookOpen } from "lucide-react";
import { MarketPrice } from "./market-price";
import { PriceChanges } from "./price-changes";
import { MobileTabList } from "./mobile-tabList";

const TradingViewChart = dynamic(() => import('./TradingViewChart'), {
  ssr: false,
  loading: () => (
    <div className="h-full w-full animate-pulse rounded-lg bg-muted" />
  ),
});

export const Content = () => {
    const isTablet = useIsTablet();

    return (
        <div className="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-x-1">
            <Forms />

            <div className="flex flex-col-reverse md:col-span-2 xl:col-span-3 2xl:col-span-4 xl:flex-row xl:space-x-1">

                {/* Orders */}
                <div className="hidden md:flex xl:flex-col h-full gap-x-1 w-full xl:w-96">
                    <OrderBooks />
                </div>

                {/* Chart - Price */}
                <div className="flex flex-col w-full">
                    <TopCurrency />
                    <div
                        className="flex xl:flex-wrap relative items-center bg-background md:rounded-r-lg lg:mt-1"
                        dir="ltr"
                    >
                        <div className="flex flex-col md:flex-row md:items-center lg:space-x-2">
                            <DialogMarketBase />
                            <TooltipProvider>
                                <Tooltip delayDuration={50}>
                                    <TooltipTrigger className="hidden lg:block cursor-pointer">
                                        <BookOpen size={13} />
                                    </TooltipTrigger>
                                    <TooltipContent className="text-xs">
                                        Tading Rules [F10]
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>

                            {/*Market price*/}
                            <MarketPrice />
                        </div>

                        {/*Last changes*/}
                        <PriceChanges />
                    </div>

                    {/* Mobile tabs */}
                    {isTablet && <MobileTabList />}

                    {/* Chart */}
                    <div className="hidden md:block h-[400px] xl:h-full w-full mt-1 bg-background">
                        <TradingViewChart />
                    </div>
                </div>
            </div>
        </div>
    );
};