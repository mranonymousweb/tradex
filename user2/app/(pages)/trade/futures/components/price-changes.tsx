import { useContext, useEffect, useRef, useState } from 'react';
import { decimalPipe, handleDecimal } from '@/helper/decimalPipe';
import { FuturesContext } from '@/store/futures';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import Countdown from './count-down';

export const PriceChanges = () => {
    const { prices, pair, config, indexPrice } = useContext(FuturesContext);
    const symbol = pair.split('_')[0];
    const scrollRef = useRef<HTMLDivElement | null>(null);
    const [canScrollLeft, setCanScrollLeft] = useState(false);
    const [canScrollRight, setCanScrollRight] = useState(false);

    const updateScrollButtons = () => {
        if (!scrollRef.current) return;
        const { scrollWidth, scrollLeft, clientWidth } = scrollRef.current;
        setCanScrollLeft(scrollLeft > 0);
        setCanScrollRight(scrollLeft + clientWidth < scrollWidth);
    };

    const handleScrollLeft = () => {
        scrollRef.current?.scrollBy({ left: -100, behavior: 'smooth' });
    };
    const handleScrollRight = () => {
        scrollRef.current?.scrollBy({ left: 100, behavior: 'smooth' });
    };

    useEffect(() => {
        const el = scrollRef.current;
        if (!el) return;

        el.addEventListener('scroll', updateScrollButtons);
        window.addEventListener('resize', updateScrollButtons);
        return () => {
            el.removeEventListener('scroll', updateScrollButtons);
            window.removeEventListener('resize', updateScrollButtons);
        };
    }, []);

    //TODO: get funding and time from api config
    return (
        <>
            <div
                ref={scrollRef}
                className={`grid grid-cols-2 md:flex md:flex-row mx-3 items-center space-x-3 
                    text-2sm text-muted-foreground py-3 overflow-auto scroll-smooth`}
            >
                <div className="flex flex-col min-w-14">
                    <span>Market</span>
                    <span className="text-foreground">
                        {handleDecimal(prices?.price)}
                    </span>
                </div>
                <div className="flex flex-col min-w-14">
                    <span>Index</span>
                    <span className="text-foreground">
                        {handleDecimal(indexPrice ?? 0)}
                    </span>
                </div>
                <div className="flex flex-col">
                    <span className="flex items-center">
                        Funding<span className="hidden md:block">/Countdown</span>
                    </span>
                    <div className="flex flex-col items-start lg:flex-row lg:items-center text-foreground">
                        <span className="text-primary">{config?.settings?.future_periodic_profit ?? `0`}%</span>
                        <span className="hidden lg:flex">/</span>
                        <span><Countdown config={config} /></span>
                    </div>
                </div>
                <div className="flex flex-col">
                    <span>24h High</span>
                    <span className="text-foreground">
                        {decimalPipe(String(prices?.high24h), 4)}
                    </span>
                </div>
                <div className="flex flex-col shrink-0">
                    <span>24h Low</span>
                    <span className="text-foreground">
                        {decimalPipe(String(prices?.low24h), 4)}
                    </span>
                </div>
                <div className="flex flex-col shrink-0">
                    <span>24h Volume({symbol})</span>
                    <span className="text-foreground">
                        {decimalPipe(String(prices?.volume), 4)}
                    </span>
                </div>
            </div>
            {canScrollLeft && (
                <button
                    type="button"
                    className={`bg-background absolute left-[15em] lg:left-[18em] top-0 
                        h-full w-6 hidden md:flex xl:hidden items-center justify-center`}
                    onClick={handleScrollLeft}
                >
                    <ChevronLeft size={15} />
                </button>
            )}
            {canScrollRight && (
                <button
                    type="button"
                    className={`bg-background absolute right-0 top-0 h-full w-6 hidden 
                        md:flex xl:hidden items-center justify-center`}
                    onClick={handleScrollRight}
                >
                    <ChevronRight size={15} />
                </button>
            )}
        </>
    );
};
