/* eslint-disable @typescript-eslint/no-explicit-any */
'use client';

import { useMemo, useState } from 'react';
import Image from 'next/image';
import { decimalPipe } from '@/helper/decimalPipe';
import { useQuery } from '@tanstack/react-query';
import {
  ColumnDef,
  getCoreRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  PaginationState,
  useReactTable,
} from '@tanstack/react-table';
import { useTranslations } from 'next-intl';
import { StockRow, StockStatus } from '@/types/stock';
import { myStocks } from '@/services/stock.services';
import { Badge } from '@/components/ui/badge';
import { CardFooter, CardTable } from '@/components/ui/card';
import { DataGrid } from '@/components/ui/data-grid';
import { DataGridPagination } from '@/components/ui/data-grid-pagination';
import { DataGridTable } from '@/components/ui/data-grid-table';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { RejectStock } from './dialog-reject';

const TableMyStock = () => {
  const t = useTranslations('stock');
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  const { data, isPending } = useQuery({
    queryKey: ['me_stocks'],
    queryFn: () => myStocks(),
  });

  const columns = useMemo<ColumnDef<StockRow>[]>(
    () => [
      {
        id: 'currency',
        accessorFn: (row) => row.currency,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground px-3">
            {t('name_currency')}
          </span>
        ),
        cell: (info) => (
          <div className="flex items-center gap-x-1 justify-start">
            <div className="flex items-center gap-x-1">
              <Image
                src={info.row.original.currency.icon}
                width={24}
                height={24}
                className="w-6 h-6 rounded-full"
                alt="icon-currency"
              />
              <span className="pt-px">{info.row.original.currency.name}</span>
            </div>
            <small className="text-muted-foreground pt-px">
              ({info.row.original.currency.symbol})
            </small>
          </div>
        ),
        enableSorting: true,
        size: 170,
        meta: {
          cellClassName: '!px-5',
        },
      },
      {
        id: 'amount',
        accessorFn: (row) => row.amount,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('interest_amount')}
          </span>
        ),
        cell: (info) => decimalPipe(String(info.getValue()), 4),
        enableSorting: false,
        size: 150,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'day',
        accessorFn: (row) => row.day,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('interest_timing')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <div className="flex justify-start items-center gap-x-1">
            {info.row.original.day}
            <small>{t('day')}</small>
          </div>
        ),
        size: 80,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
      {
        id: 'staking',
        accessorFn: (row) => row.staking.min_day,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('interest_time')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <div className="flex justify-center items-center gap-x-1">
            {info.row.original.staking.min_day}
            <small>{t('day')}</small>
          </div>
        ),
        size: 170,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
      {
        id: 'daily_profit_percent',
        accessorFn: (row) => row.daily_profit_percent,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('profit_dilay_amount')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <span className="text-green">
            %{info.row.original.daily_profit_percent}
          </span>
        ),
        size: 100,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
      {
        id: 'daily_profit_percent',
        accessorFn: (row) => row.daily_profit_percent,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('total_profit')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <span>{decimalPipe(info.row.original.total_profit, 6)}</span>
        ),
        size: 120,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
      {
        id: 'status',
        accessorFn: (row) => row.status,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('status')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <Badge
            variant={
              info.row.original.status === StockStatus.ACTIVE
                ? 'success'
                : info.row.original.status === StockStatus.WATTING
                  ? 'warning'
                  : 'destructive'
            }
            size="lg"
            shape="default"
            appearance="outline"
            className="text-nowrap"
          >
            {info.row.original.status === StockStatus.ACTIVE
              ? t('status_option.active')
              : info.row.original.status === StockStatus.WATTING
                ? t('status_option.waiting')
                : t('status_option.canceled')}
          </Badge>
        ),
        size: 85,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
      {
        id: 'created_at',
        accessorFn: (row) => row.created_at,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground py-2.5">
            {t('create_at')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => info.getValue(),
        size: 100,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
      {
        id: 'created_at',
        accessorFn: (row) => row.created_at,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground py-2.5">
            {t('operation')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => {
          const statusActive = info.row.original.status === StockStatus.ACTIVE;
          return statusActive ? (
            <RejectStock currency={info.row.original} />
          ) : (
            '--'
          );
        },
        size: 80,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
    ],
    [t],
  );

  const table = useReactTable({
    columns,
    data: data?.data ?? [],
    pageCount: data?.meta?.last_page || 0,
    getRowId: (row: any) => String(row.id),
    state: { pagination },
    onPaginationChange: setPagination,
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    manualPagination: true,
  });

  return (
    <>
      <DataGrid
        table={table}
        recordCount={data?.meta?.total || 0}
        isLoading={isPending}
        loadingMode="spinner"
        tableLayout={{
          columnsPinnable: true,
          columnsMovable: true,
          columnsVisibility: true,
          cellBorder: false,
          dense: true,
        }}
      >
        <>
          <CardTable>
            <ScrollArea>
              <DataGridTable />
              <ScrollBar orientation="horizontal" />
            </ScrollArea>
          </CardTable>
          <CardFooter>
            <DataGridPagination />
          </CardFooter>
        </>
      </DataGrid>
    </>
  );
};

export { TableMyStock };
