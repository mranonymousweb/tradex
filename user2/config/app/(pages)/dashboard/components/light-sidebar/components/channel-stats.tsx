'use client';

import { Fragment } from 'react';
import { decimalPipe, handleDecimal } from '@/helper/decimalPipe';
import { dashboardCurrencies } from '@/helper/markets/markets';
import { MarketCurrency } from '@/types/market-currency';
import { Card, CardContent } from '@/components/ui/card';
import { RenderItemPulse } from './render-pulse';

type IChannelStatsItems = Array<MarketCurrency>;

const ChannelStats = () => {
  const { currencies, isPending } = dashboardCurrencies();
  if (isPending) return RenderItemPulse();

  const renderItem = (item: MarketCurrency, index: number) => {
    const sign = Math.sign(item.changePercent);
    const isDown = sign === -1;

    return (
      <Card key={index} className="group relative overflow-hidden">
        <span
          aria-hidden
          className={`pointer-events-none absolute -top-10 -end-10 size-28 rounded-full blur-2xl opacity-30 transition-opacity group-hover:opacity-60 ${isDown ? 'bg-destructive' : 'bg-green'}`}
        />
        <CardContent className="relative p-5 flex flex-col justify-between gap-6 h-full">
          <div className="flex justify-between items-center">
            <div className="flex gap-2 items-center">
              <img
                src={item.icon ?? '/media/images/currency-icon.webp'}
                className="size-8 rounded-full ring-2 ring-border"
                alt={item.symbol}
              />
              <div className="flex items-center text-sm font-semibold">
                <span>{item.symbol}</span>
                <span className="text-muted-foreground">/USDT</span>
              </div>
            </div>
          </div>
          <div className="flex flex-wrap gap-x-2 gap-y-1 items-center justify-between">
            <span className="tabular text-lg font-bold text-mono">
              {handleDecimal(item.last)}
            </span>
            <span
              dir="ltr"
              className={`tabular rounded-md px-1.5 py-0.5 text-xs font-semibold ${isDown ? 'bg-destructive/10 text-destructive' : 'bg-green/10 text-green'}`}
            >
              {sign === 1 && '+'}
              {decimalPipe(item.changePercent, 2)}
            </span>
          </div>
        </CardContent>
      </Card>
    );
  };

  return (
    <Fragment>
      {currencies?.map((item: any, index: number) => renderItem(item, index))}
    </Fragment>
  );
};

export { ChannelStats, type MarketCurrency, type IChannelStatsItems };
