/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable react-hooks/exhaustive-deps */

'use client';

import { useEffect, useMemo, useState } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { useQuery } from '@tanstack/react-query';
import {
  ColumnDef,
  getCoreRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  PaginationState,
  RowSelectionState,
  useReactTable,
} from '@tanstack/react-table';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { swapRow } from '@/types/swap';
import { listOrdersSwap } from '@/services/swap.services';
import {
  Card,
  CardFooter,
  CardHeader,
  CardTable,
  CardTitle,
  CardToolbar,
} from '@/components/ui/card';
import { DataGrid } from '@/components/ui/data-grid';
import { DataGridPagination } from '@/components/ui/data-grid-pagination';
import { DataGridTable } from '@/components/ui/data-grid-table';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { DECIMAL_RECEIVE } from './swap/config';
import { StatusSwap } from './swap/status';
import { FilterList } from './table-filter';

const Table = () => {
  const t = useTranslations('swap.table');
  const [isFilter, setIsFilter] = useState(false);
  const [searchQuery, setSearchQuery] = useState<any[] | null>(null);
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  useEffect(() => {
    setPagination((prev) => ({
      ...prev,
      pageIndex: 0,
    }));
  }, [searchQuery]);

  const path = `page=${pagination.pageIndex + 1}&${searchQuery}`;
  const { data, isPending } = useQuery({
    queryKey: ['swap-orders', pagination.pageIndex + 1, path],
    queryFn: () => listOrdersSwap(path),
    enabled: Boolean(path),
    refetchInterval: (query) => {
      const item = query?.state?.data;
      if (!item) return false;
      const isWaiting = item.data.some(
        (i: any) =>
          i.status === 'waiting' ||
          i.status === 'waiting_by_supplier' ||
          i.status === 'rejected_by_supplier',
      );
      return isWaiting ? 10000 : false;
    },
    refetchOnWindowFocus: false,
  });

  const [rowSelection] = useState<RowSelectionState>({});
  const columns = useMemo<ColumnDef<swapRow>[]>(
    () => [
      {
        id: 'id',
        accessorFn: (row) => row.id,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('id')}
          </span>
        ),
        cell: (info) => (
          <div className="flex items-center justify-start text-foreground font-normal">
            {info.row.original.id ?? '--'}
          </div>
        ),
        enableSorting: false,
        size: 100,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'from_amount',
        accessorFn: (row) => row.from_amount,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('payment_currency')}
          </span>
        ),
        cell: (info) => {
          const decimal =
            info.row.original.from_currency_symbol === 'IRT'
              ? 0
              : DECIMAL_RECEIVE;
          return (
            <div className="flex items-center justify-start gap-1">
              <span>{decimalPipe(info.row.original.from_amount, decimal)}</span>
              <span className="text-secondary-foreground text-xs">
                {info.row.original.from_currency_symbol}
              </span>
            </div>
          );
        },
        enableSorting: true,
        size: 150,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'to_amount',
        accessorFn: (row) => row.to_amount,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] whitespace-nowrap text-accent-foreground">
            {t('received_currency')}
          </span>
        ),
        cell: (info) => {
          const decimal =
            info.row.original.to_currency_symbol === 'IRT'
              ? 0
              : DECIMAL_RECEIVE;
          return (
            <div className="flex items-center justify-start gap-1">
              <span>{decimalPipe(info.row.original.to_final, decimal)}</span>
              <span className="text-secondary-foreground text-xs">
                {info.row.original.to_currency_symbol}
              </span>
            </div>
          );
        },
        enableSorting: true,
        size: 100,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'fee',
        accessorFn: (row) => row.fee,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('fee')}
          </span>
        ),
        cell: (info) => {
          const decimal =
            info.row.original.to_currency_symbol === 'IRT'
              ? 0
              : DECIMAL_RECEIVE;
          return (
            <div className="flex items-center justify-start gap-1">
              {decimalPipe(info.row.original.fee, decimal)}
              <span className="text-secondary-foreground text-xs">
                {info.row.original.to_currency_symbol}
              </span>
            </div>
          );
        },
        enableSorting: true,
        size: 100,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'created_at',
        accessorFn: (row) => row.created_at,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('date')}
          </span>
        ),
        cell: (info) => info.getValue(),
        enableSorting: true,
        size: 150,
        meta: {
          cellClassName: '',
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
        cell: (info) => <StatusSwap info={info} />,
        size: 100,
        meta: {
          cellClassName: '',
        },
      },
    ],
    [],
  );

  useEffect(() => {
    const selectedRowIds = Object.keys(rowSelection);

    if (selectedRowIds.length > 0) {
      toast(`Total ${selectedRowIds.length} are selected.`, {
        description: `Selected row IDs: ${selectedRowIds}`,
        action: {
          label: 'Undo',
          onClick: () => console.log('Undo'),
        },
      });
    }
  }, [rowSelection]);

  const table = useReactTable({
    columns,
    data: data?.data,
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

  const Toolbar = () => {
    return (
      <CardToolbar>
        <div className="flex flex-wrap items-center gap-2.5">
          <FilterList
            isFilter={isFilter}
            setIsFilter={setIsFilter}
            setSearchQuery={setSearchQuery}
          />
        </div>
      </CardToolbar>
    );
  };

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
          cellBorder: true,
          width: 'auto',
        }}
      >
        <Card>
          <CardHeader>
            <CardTitle>{t('list_orders')}</CardTitle>
            <Toolbar />
          </CardHeader>
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

export { Table };
