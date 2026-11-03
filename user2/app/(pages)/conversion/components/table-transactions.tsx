/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable react-hooks/exhaustive-deps */

'use client';

import { useMemo, useState } from 'react';
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
import { CurrencyConversionLogRow } from '@/types/wallet';
import { logsConversion } from '@/services/currency-conversion';
import { CardFooter, CardTable } from '@/components/ui/card';
import { DataGrid } from '@/components/ui/data-grid';
import { DataGridPagination } from '@/components/ui/data-grid-pagination';
import { DataGridTable } from '@/components/ui/data-grid-table';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';

const TableTransactions = () => {
  const t = useTranslations('wallet.conversion');
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  const { data, isPending } = useQuery({
    queryKey: ['log_conversion'],
    queryFn: () => logsConversion(),
  });

  const columns = useMemo<ColumnDef<CurrencyConversionLogRow>[]>(
    () => [
      {
        id: 'id',
        accessorFn: (row) => row.id,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground px-3">
            {t('code_transaction')}
          </span>
        ),
        cell: (info) => info.getValue(),
        enableSorting: true,
        size: 150,
        meta: {
          cellClassName: '!px-5',
        },
      },
      {
        id: 'symbol',
        accessorFn: (row) => row.symbol,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('name_currency')}
          </span>
        ),
        cell: (info) => info.getValue(),
        enableSorting: false,
        size: 150,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'amount',
        accessorFn: (row) => row.amount,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('amount')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <span>{decimalPipe(info.row.original.amount, 14)}</span>
        ),
        size: 150,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
      {
        id: 'fee',
        accessorFn: (row) => row.fee,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('fee')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => <span>{decimalPipe(info.row.original.fee, 16)}</span>,
        size: 150,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
      {
        id: 'total',
        accessorFn: (row) => row.total,
        header: () => (
          <span className="flex justify-start font-normal gap-x-1 items-center text-[0.8125rem] text-accent-foreground">
            {t('received')}
            <small className="pt-1">(USDT)</small>
          </span>
        ),
        enableSorting: false,
        cell: (info) => <span>{decimalPipe(info.row.original.total, 14)}</span>,
        size: 150,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
      {
        id: 'created_at',
        accessorFn: (row) => row.created_at,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground py-2.5">
            {t('date')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => info.getValue(),
        size: 150,
        meta: {
          cellClassName: 'text-secondary-foreground',
        },
      },
    ],
    [],
  );

  const table = useReactTable({
    columns,
    data: data?.data ?? [],
    pageCount: data?.meta?.last_page || 0,
    getRowId: (row: any) => String(row.symbol),
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

export { TableTransactions };
