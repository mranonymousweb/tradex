'use client';

import Image from 'next/image';
import Link from 'next/link';
import { getCompact } from '@/helper/compact';
import { decimalPipe, handleDecimal } from '@/helper/decimalPipe';
import { marketsCurrencies } from '@/helper/markets/markets';
import CurrencyIcon from '@/public/media/images/currency-icon.webp';
import { ReceiptText } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { TableCurrencies } from './components/table-currencies';

export const ContentMarkets = () => {
  const t = useTranslations('markets');
  const { USD_PRICE } = marketsCurrencies();

  const columns = [
    {
      id: 'currency',
      accessorFn: (row: any) => row.currency,
      header: () => (
        <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
          {t('name_currency')}
        </span>
      ),
      cell: (info: any) => (
        <div className="flex items-center gap-x-1 justify-start">
          <div className="flex items-center gap-x-1">
            <Image
              src={info.row.original.icon ?? CurrencyIcon}
              width={24}
              height={24}
              className="w-6 h-6 rounded-full"
              alt="icon-currency"
            />
            <span className="pt-px">{info.row.original.name}</span>
          </div>
          <small className="text-muted-foreground pt-px">
            ({info.row.original.symbol})
          </small>
        </div>
      ),
      enableSorting: true,
      size: 200,
      meta: {
        cellClassName: '',
      },
    },
    {
      id: 'fiat',
      accessorFn: (row: any) => row.last,
      header: () => (
        <div className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground items-center gap-x-1">
          <span>{t('price')}</span>
          <small>(IRT)</small>
        </div>
      ),
      cell: (info: any) => {
        const lastPrice = Number(info.row.original.last);
        const usdtIRT = info.row.original.usdtIRT;
        const price_irt = USD_PRICE === 0 ? usdtIRT : USD_PRICE;

        const price = lastPrice * price_irt;
        const decimal = lastPrice > 1 ? 0 : 6;
        return <span>{decimalPipe(price, decimal)}</span>;
      },
      enableSorting: false,
      size: 150,
      meta: {
        cellClassName: '',
      },
    },
    {
      id: 'last',
      accessorFn: (row: any) => row.last,
      header: () => (
        <div className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground items-center gap-x-1">
          <span>{t('price')}</span>
          <small>(USDT)</small>
        </div>
      ),
      cell: (info: any) => <span>{handleDecimal(info.row.original.last)}</span>,
      enableSorting: false,
      size: 150,
      meta: {
        cellClassName: '',
      },
    },
    {
      id: 'changePercent',
      accessorFn: (row: any) => row.changePercent,
      header: () => (
        <div className="flex justify-start font-normal text-[0.8125rem] whitespace-nowrap text-accent-foreground items-center gap-x-1">
          <span>{t('changes')}</span>
          <small>(USDT)</small>
        </div>
      ),
      cell: (info: any) => {
        const isPercent = Math.sign(info.row.original.changePercent);
        return (
          <Badge
            variant={`${isPercent === -1 ? 'destructive' : 'success'}`}
            appearance="ghost"
            dir="ltr"
          >
            {isPercent === 1 && '+'}
            {decimalPipe(info.row.original.changePercent, 2)}%
          </Badge>
        );
      },
      enableSorting: true,
      size: 150,
      meta: {
        cellClassName: '',
      },
    },
    {
      id: 'volume',
      accessorFn: (row: any) => row.volume,
      header: () => (
        <div className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground items-center gap-x-1">
          <span>{t('changes_volume')}</span>
          <small>(USDT)</small>
        </div>
      ),
      enableSorting: false,
      cell: (info: any) => <span>{getCompact(info.row.original.volume)}</span>,
      size: 150,
      meta: {
        cellClassName: 'text-secondary-foreground',
      },
    },
    {
      id: 'quoteVolume',
      accessorFn: (row: any) => row.quoteVolume,
      header: () => (
        <div className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground items-center gap-x-1">
          <span>{t('trading_valume')}</span>
          <small>(USDT)</small>
        </div>
      ),
      enableSorting: false,
      cell: (info: any) => (
        <span>{getCompact(info.row.original.quoteVolume)}</span>
      ),
      size: 150,
      meta: {
        cellClassName: 'text-secondary-foreground',
      },
    },
    {
      id: 'last',
      accessorFn: (row: any) => row.id,
      header: () => (
        <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground py-3">
          {t('operation')}
        </span>
      ),
      enableSorting: false,
      cell: (info: any) => (
        <Button variant="ghost" mode="link">
          <Link
            target="_blank"
            href={info.row.original.link ?? '/#'}
            className="text-muted-foreground"
          >
            <ReceiptText />
          </Link>
        </Button>
      ),
      size: 100,
      meta: {
        cellClassName: '',
      },
    },
  ];
  return <TableCurrencies columns={columns} />;
};
