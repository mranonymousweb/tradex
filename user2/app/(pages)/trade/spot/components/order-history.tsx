'use client';

import { useContext, useMemo, useState } from 'react';
import { decimalPipe, handleDecimal } from '@/helper/decimalPipe';
import { localeDate } from '@/helper/formatDate';
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
import { getOrderHistory } from '@/services/spot.services';
import { Card, CardFooter, CardTable } from '@/components/ui/card';
import { DataGrid } from '@/components/ui/data-grid';
import { DataGridPagination } from '@/components/ui/data-grid-pagination';
import { DataGridTable } from '@/components/ui/data-grid-table';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';

const OrderHistory = ({ showAll }: { showAll: boolean }) => {
  const t = useTranslations('spot');
  const lang = useLocale();
  const { pair } = useContext(MainSpotContext);
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  const path = `page=${pagination.pageIndex + 1}&${!showAll && `pairs=${pair}`}`;
  const { data: history, isPending } = useQuery({
    queryKey: ['history', pagination.pageIndex, path],
    queryFn: () => getOrderHistory(path),
  });

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
        cell: (info) => info.getValue(),
        enableSorting: true,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'done_at',
        accessorFn: (row) => row.done_at,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('date')}
          </span>
        ),
        cell: (info) => localeDate(String(info.getValue()), lang),
        enableSorting: true,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'pairs',
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
        size: 100,
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
            <div className="flex justify-center gap-x-1">
              <span>{decimalPipe(info.getValue() as string, 6)}</span>
              <span>{symbol}</span>
            </div>
          );
        },
        enableSorting: false,
        meta: {
          cellClassName: 'text-center text-xs text-[#C99400]',
        },
      },
      {
        id: 'total',
        accessorFn: (row) => row.total,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('payment_total')}
          </span>
        ),
        cell: (info) => {
          const symbol = info.row.original.pairs.split('_')[1];
          return (
            <div className="flex justify-center gap-x-1">
              <span>{decimalPipe(info.getValue() as string, 6)}</span>
              <span>{symbol}</span>
            </div>
          );
        },
        enableSorting: false,
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
            <div className="flex justify-center gap-x-1">
              <span>{decimalPipe(info.getValue() as string, 6)}</span>
              <span>{symbol}</span>
            </div>
          );
        },
        enableSorting: false,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'fee',
        accessorFn: (row) => row.fee,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('fee')}
          </span>
        ),
        cell: (info) => {
          const symbol = info.row.original.pairs.split('_')[0];
          const baseSymbol = info.row.original.pairs.split('_')[1];
          const side = info.row.original.side;
          return (
            <div className="flex justify-center gap-x-1">
              <span>{decimalPipe(info.getValue() as string, 8)}</span>
              <span>{ side === 1 ? symbol : baseSymbol}</span>
            </div>
          );
        },
        enableSorting: false,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
      {
        id: 'final_amount',
        accessorFn: (row) => row.final_amount,
        header: () => (
          <span className="flex justify-center text-muted-foreground text-xs pt-2">
            {t('receive_amount')}
          </span>
        ),
        cell: (info) => {
          const symbol = info.row.original.pairs.split('_')[0];
          const baseSymbol = info.row.original.pairs.split('_')[1];
          const side = info.row.original.side;
          const total = info.row.original.total;
          const fee = info.row.original.fee;
          const finalAmountSell = Number(total) - Number(fee);
           const finalAmount = side === 1 ? info.getValue() as string : String(finalAmountSell);
           const minus = Math.sign(Number(finalAmount));
           const colorText = minus === -1 && 'text-destructive';
          return (
            <div className={`flex justify-center gap-x-1 ${colorText}`}>
              <span>{decimalPipe(Number(finalAmount), 6)}</span>
              <span>{ side === 1 ? symbol : baseSymbol}</span>
            </div>
          );
        },
        enableSorting: false,
        meta: {
          cellClassName: 'text-center text-xs',
        },
      },
    ],
    [lang, t],
  );

  const table = useReactTable({
    columns,
    data: history?.data ?? [],
    pageCount: history?.meta?.last_page || 0,
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
        recordCount={history?.meta.total || 0}
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

export { OrderHistory };
