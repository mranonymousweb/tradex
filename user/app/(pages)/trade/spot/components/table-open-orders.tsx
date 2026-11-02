/* eslint-disable @typescript-eslint/no-explicit-any */

'use client';

import { useContext, useMemo, useState } from 'react';
import { decimalPipe, handleDecimal } from '@/helper/decimalPipe';
import { localeDate } from '@/helper/formatDate';
import { matchOrder } from '@/helper/spot/ordersMarket';
import { MainSpotContext } from '@/store/trade-spot';
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
import { useLocale, useTranslations } from 'next-intl';
import { Order } from '@/types/spot';
import { ordersMarket } from '@/services/spot.services';
import { Card, CardFooter, CardTable } from '@/components/ui/card';
import { DataGrid } from '@/components/ui/data-grid';
import { DataGridPagination } from '@/components/ui/data-grid-pagination';
import { DataGridTable } from '@/components/ui/data-grid-table';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { CloseOrder } from './close-order';

const OpenOrders = ({
  updateOrder,
  showAll,
}: {
  updateOrder: any;
  showAll: boolean;
}) => {
  const t = useTranslations('spot');
  const lang = useLocale();
  const { pair } = useContext(MainSpotContext);
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  const path = `status=4&page=${pagination.pageIndex + 1}&${!showAll && `pairs=${pair}`}`;
  const { data, isPending } = useQuery({
    queryKey: ['order-market', pagination.pageIndex, path],
    queryFn: () => ordersMarket(path),
  });
  const orders = matchOrder(data?.data, updateOrder);

  const columns = useMemo<ColumnDef<Order>[]>(
    () => [
      {
        id: 'id',
        accessorFn: (row) => row.id,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('order_id')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => info.getValue(),
        size: 100,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'open_at',
        accessorFn: (row) => row.open_at,
        header: () => (
          <span className="flex justify-center text-muted-foreground">
            {t('date')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => localeDate(String(info.getValue()), lang),
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'market',
        accessorFn: (row) => row.pairs,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('market')}
          </span>
        ),
        cell: (info) => info.getValue(),
        enableSorting: true,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'type',
        accessorFn: (row) => row.type,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('order_type')}
          </span>
        ),
        cell: (info) => {
          const type = info.getValue();
          return (
            <span>
              {type === 1
                ? 'Market'
                : type === 2
                  ? 'Limit'
                  : type === 3
                    ? 'Stop Limit'
                    : 'Stop Market'}
            </span>
          );
        },
        enableSorting: false,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'side',
        accessorFn: (row) => row.side,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('transaction_type')}
          </span>
        ),
        cell: (info) => (
          <span
            className={`${info.row.original.side === 1 ? 'text-green' : 'text-destructive'}`}
          >
            {info.row.original.side === 1 ? t('buy') : t('sell')}
          </span>
        ),
        enableSorting: false,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'price',
        accessorFn: (row) => row.price,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('price')}
          </span>
        ),
        cell: (info) => handleDecimal(info.getValue() as string),
        enableSorting: true,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'amount',
        accessorFn: (row) => row.amount,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('amount')}
          </span>
        ),
        cell: (info) => {
          const symbol = info.row.original.pairs.split('_')[0];
          return (
            <span>
              {decimalPipe(info.getValue() as string, 6)} {symbol}
            </span>
          );
        },
        enableSorting: true,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'filled_amount',
        accessorFn: (row) => row.filled_amount,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('fill_amount')}
          </span>
        ),
        cell: (info) => {
          const symbol = info.row.original.pairs.split('_')[0];
          return (
            <span>
              {decimalPipe(info.getValue() as string, 6)} {symbol}
            </span>
          );
        },
        enableSorting: true,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'total',
        accessorFn: (row) => row.total,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('total')}
          </span>
        ),
        cell: (info) => {
          const symbol = info.row.original.pairs.split('_')[1];
          return (
            <span>
              {decimalPipe(info.getValue() as string, 4)} {symbol}
            </span>
          );
        },
        enableSorting: true,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'stop_price',
        accessorFn: (row) => row.stop_price,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('price_stop')}
          </span>
        ),
        cell: (info) => {
          const symbol = info.row.original.pairs.split('_')[1];
          return (
            <span>
              {decimalPipe(info.getValue() as string, 6)} {symbol}
            </span>
          );
        },
        enableSorting: true,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'id',
        accessorFn: (row) => row.id,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('operation')}
          </span>
        ),
        cell: (info) => <CloseOrder id={Number(info.getValue())} />,
        enableSorting: true,
        meta: {
          cellClassName: 'text-center flex justify-center text-xs',
        },
      },
    ],
    [lang, t],
  );

  const table = useReactTable({
    columns,
    data: orders ?? [],
    pageCount: data?.meta?.last_page || 0,
    getRowId: (row) => String(row.id),
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
        recordCount={data?.meta.total || 0}
        isLoading={isPending}
        loadingMode="spinner"
        tableLayout={{
          columnsPinnable: true,
          columnsMovable: true,
          columnsVisibility: true,
          headerBackground: false,
          rowRounded: false,
          rowBorder: false,
          headerBorder: false,
          cellBorder: false,
        }}
      >
        <Card>
          <CardTable>
            <ScrollArea>
              <DataGridTable />
              <ScrollBar orientation="horizontal" />
            </ScrollArea>
          </CardTable>
          <CardFooter>
            <DataGridPagination />
          </CardFooter>
        </Card>
      </DataGrid>
    </>
  );
};

export { OpenOrders };
