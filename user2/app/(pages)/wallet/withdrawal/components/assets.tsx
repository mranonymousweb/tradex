'use client';

import { useContext, useMemo, useState } from 'react';
import dynamic from 'next/dynamic';
import { decimalPipe } from '@/helper/decimalPipe';
import { WalletContext } from '@/store/wallet';
import { ApexOptions } from 'apexcharts';
import { useTranslations } from 'next-intl';
import { Currency } from '@/types/wallet';
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';

const ApexChart = dynamic(() => import('react-apexcharts'), { ssr: false });

interface Props {
  title: string;
}

const Assets = ({ title }: Props) => {
  const t = useTranslations('wallet.withdrawal');
  const { selectedCoin, currencies } = useContext(WalletContext);

  const assets = currencies?.find((cl: Currency) => cl.id === +selectedCoin);

  const isIRT = assets?.type === 2;
  const decimal = isIRT ? 0 : 12;

  const data: number[] =
    Number(assets?.user_asset.total) === 0
      ? [100]
      : [
          Number(assets?.user_asset?.blockPercentage),
          Number(assets?.user_asset?.futurePercentage),
          Number(assets?.user_asset?.spotPercentage),
        ];
  const colors: string[] =
    assets?.user_asset?.total === 0
      ? ['oklch(87.2% 0.01 258.338)']
      : [
          'oklch(87.2% 0.01 258.338)',
          'var(--color-blue-500)',
          'var(--color-green)',
        ];

  const options: ApexOptions = {
    series: data,
    colors: colors,
    fill: {
      colors: colors,
    },
    chart: {
      type: 'donut',
    },
    stroke: {
      show: true,
      width: 0,
    },
    dataLabels: {
      enabled: false,
    },
    plotOptions: {
      pie: {
        expandOnClick: false,
      },
    },
    legend: {
      show: false,
    },
    responsive: [
      {
        breakpoint: 480,
        options: {
          chart: {
            width: 100,
          },
          legend: {
            position: 'bottom',
          },
        },
      },
    ],
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle>{title}</CardTitle>
      </CardHeader>
      <CardContent className="flex flex-col">
        <>
          <div className="flex justify-center">
            <ApexChart
              key={selectedCoin}
              id="contributions_chart"
              options={options}
              series={options.series}
              type="donut"
              width="120"
              height="120"
            />
          </div>
          <div className="flex flex-col gap-y-2">
            <div className="flex justify-between items-center">
              <div className="flex items-center gap-x-2">
                <div className="w-[8px] h-[6px] rounded-md bg-success"></div>
                <span className="text-xs text-secondary-foreground">
                  {t('asste_main')}
                </span>
              </div>
              <div className="text-xs flex items-center gap-x-1">
                {decimalPipe(String(assets?.user_asset?.spot), decimal)}
                <small className="text-foreground">{assets?.symbol}</small>
              </div>
            </div>
            <div className="flex justify-between items-center gap-x-4">
              <div className="flex items-center gap-x-2">
                <div className="w-[8px] h-[6px] rounded-md bg-primary"></div>
                <span className="text-xs text-secondary-foreground">
                  {t('asste_futures')}
                </span>
              </div>
              <span className="text-xs flex items-center gap-x-1">
                {decimalPipe(String(assets?.user_asset?.future), decimal)}
                <small className="text-foreground">{assets?.symbol}</small>
              </span>
            </div>
            <div className="flex justify-between items-center gap-x-4">
              <div className="flex items-center gap-x-2">
                <div className="w-[8px] h-[6px] rounded-md bg-secondary"></div>
                <span className="text-xs text-secondary-foreground">
                  {t('asste_block')}
                </span>
              </div>
              <span className="text-xs flex items-center gap-x-1">
                {decimalPipe(String(assets?.user_asset?.block), decimal)}
                <small>{assets?.symbol}</small>
              </span>
            </div>
          </div>
        </>
      </CardContent>
      <CardFooter className="flex justify-between items-center">
        <span className="text-secondary-foreground text-xs">
          {t('asste_total')}
        </span>
        <div className="text-sm flex gap-x-1 font-medium items-center">
          {decimalPipe(`${assets?.user_asset?.total}`, decimal)}
          <small>{assets?.symbol}</small>
        </div>
      </CardFooter>
    </Card>
  );
};

export { Assets, type Props };
