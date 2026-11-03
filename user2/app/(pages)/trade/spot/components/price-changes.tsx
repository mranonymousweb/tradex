import { useContext, useEffect, useRef, useState } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { currenciesPrice } from '@/helper/spot/currencyPrice';
import { MainSpotContext } from '@/store/trade-spot';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useTranslations } from 'next-intl';

export const PriceChanges = () => {
  const t = useTranslations('spot');
  const { pair } = useContext(MainSpotContext);
  const symbol = pair.split('_')[0];
  const base = pair.split('_')[1];
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

    updateScrollButtons();
    el.addEventListener('scroll', updateScrollButtons);
    window.addEventListener('resize', updateScrollButtons);
    return () => {
      el.removeEventListener('scroll', updateScrollButtons);
      window.removeEventListener('resize', updateScrollButtons);
    };
  }, []);

  const { currencies } = currenciesPrice(pair, base);

  const prices = currencies.find((currency) => currency.pairs === pair);

  const isMinus = Math.sign(Number(prices?.changePercent));

  const decimalValue = base?.toLocaleLowerCase() === 'irt' ? 0 : 2;

  return (
    <>
      <div
        ref={scrollRef}
        className="grid grid-cols-2 md:flex md:flex-row mx-3 items-center space-x-3 text-2sm text-muted-foreground py-3 overflow-auto scroll-smooth"
      >
        <div className="flex flex-col">
          <span className="text-xs">24h {t('chg')}</span>
          <span
            className={`text-xs text-green ${isMinus === -1 && '!text-destructive'}`}
          >
            {decimalPipe(prices?.changePercent ?? 0, 2)} %
          </span>
        </div>
        <div className="flex flex-col">
          <span className="text-xs">24h {t('high')}</span>
          <span className="text-foreground">
            {decimalPipe(prices?.high24h ?? 0, decimalValue)}
          </span>
        </div>
        <div className="flex flex-col shrink-0">
          <span className="text-xs">24h {t('low')}</span>
          <span className="text-foreground">
            {decimalPipe(prices?.low24h ?? 0, decimalValue)}
          </span>
        </div>
        <div className="flex flex-col shrink-0">
          <span className="text-xs">
            24h {t('volume')} ({symbol})
          </span>
          <span className="text-foreground">
            {decimalPipe(prices?.volume ?? 0, decimalValue)}
          </span>
        </div>
        <div className="flex flex-col shrink-0">
          <span className="text-xs">
            24h {t('volume')} ({base})
          </span>
          <span className="text-foreground">
            {decimalPipe(prices?.quoteVolume ?? 0, decimalValue)}
          </span>
        </div>
      </div>
      {canScrollLeft && (
        <button
          type="button"
          className={`bg-background absolute left-[15em] lg:left-[18em] top-0 h-full w-6 hidden md:flex xl:hidden items-center justify-center`}
          onClick={handleScrollLeft}
        >
          <ChevronLeft size={15} />
        </button>
      )}
      {canScrollRight && (
        <button
          type="button"
          className={`bg-background absolute right-0 top-0 h-full w-6 hidden md:flex xl:hidden items-center justify-center`}
          onClick={handleScrollRight}
        >
          <ChevronRight size={15} />
        </button>
      )}
    </>
  );
};
