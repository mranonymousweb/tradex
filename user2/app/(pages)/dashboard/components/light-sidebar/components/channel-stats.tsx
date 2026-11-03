'use client';

import { Fragment } from 'react';
import { decimalPipe, handleDecimal } from '@/helper/decimalPipe';
import { dashboardCurrencies } from '@/helper/markets/markets';
import { MarketCurrency } from '@/types/market-currency';
import { toAbsoluteUrl } from '@/lib/helpers';
import { Card, CardContent } from '@/components/ui/card';
import { RenderItemPulse } from './render-pulse';

type IChannelStatsItems = Array<MarketCurrency>;

const ChannelStats = () => {
  const { currencies, isPending } = dashboardCurrencies();
  if (isPending) return RenderItemPulse();

  const renderItem = (item: MarketCurrency, index: number) => {
    const isPercent = Math.sign(item.changePercent);

    return (
      <Card key={index}>
        <CardContent className="p-5 flex flex-col justify-between gap-6 h-full bg-cover rtl:bg-[left_top_-1.7rem] bg-[right_top_-1.7rem] bg-no-repeat channel-stats-bg">
          <div className="flex justify-between items-center">
            <div className="flex gap-1 items-center">
              <img
                src={item.icon ?? '/media/images/currency-icon.webp'}
                className="w-7 rounded-full"
                alt="image"
              />
              <div className="flex items-center text-sm font-semibold pt-1.5">
                <span>{item.symbol}</span>
                <span className="text-muted-foreground">/USDT</span>
              </div>
            </div>
          </div>
          <div className="flex gap-x-2 items-center">
            <span className="text-lg font-semibold text-mono min-w-20">
              {handleDecimal(item.last)}
            </span>
            <span
              dir="ltr"
              className={`${isPercent === -1 ? 'text-destructive' : 'text-green'}`}
            >
              {isPercent === 1 && '+'}
              {decimalPipe(item.changePercent, 2)}
            </span>
          </div>
        </CardContent>
      </Card>
    );
  };

  return (
    <Fragment>
      <style>
        {`
          .channel-stats-bg {
            background-image: url('${toAbsoluteUrl('/media/images/2600x1600/bg-3.png')}');
          }
          .dark .channel-stats-bg {
            background-image: none
          }
        `}
      </style>
      {currencies?.map((item: any, index: number) => renderItem(item, index))}
    </Fragment>
  );
};

export { ChannelStats, type MarketCurrency, type IChannelStatsItems };
