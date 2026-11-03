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
import { LogsSubsetRow } from '@/types/marketing';
import { getLogs } from '@/services/marketing.services';
import {
  Card,
  CardFooter,
  CardHeader,
  CardTable,
  CardTitle,
} from '@/components/ui/card';
import { DataGrid } from '@/components/ui/data-grid';
import { DataGridPagination } from '@/components/ui/data-grid-pagination';
import { DataGridTable } from '@/components/ui/data-grid-table';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';

const Logs = ({ id }: { id: string }) => {
  const t = useTranslations('marketing.table');
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  const { data: logs, isPending } = useQuery({
    queryKey: ['marketing-logs', pagination.pageIndex, id],
    queryFn: () => getLogs(id, pagination.pageIndex + 1),
  });
  const [rowSelection] = useState<RowSelectionState>({});

  const columns = useMemo<ColumnDef<LogsSubsetRow>[]>(
    () => [
      {
        id: 'user_id',
        accessorFn: (row) => row.user_id,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('id_user')}
          </span>
        ),
        cell: (info) => info.getValue(),
        enableSorting: false,
        size: 100,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'symbol',
        accessorFn: (row) => row.symbol,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('currency')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => info.getValue(),
        size: 100,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'created_at',
        accessorFn: (row) => row.created_at,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('date_transactions')}
          </span>
        ),
        cell: (info) => info.getValue(),
        enableSorting: true,
        size: 100,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'total_amount',
        accessorFn: (row) => row.total_amount,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('total_amount')}
          </span>
        ),
        cell: (info) => (
          <div className="flex items-center justify-start text-foreground font-normal">
            {decimalPipe(info.row.original.total_amount, 5)}
          </div>
        ),
        enableSorting: true,
        size: 100,
        meta: {
          cellClassName: '',
        },
      },

      {
        id: 'parent_amount',
        accessorFn: (row) => row.parent_amount,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('received_amount')}
          </span>
        ),
        cell: (info) => {
          return (
            <div className="flex items-center justify-start text-foreground font-normal">
              {decimalPipe(info.row.original.parent_amount, 5)}
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
        id: 'section',
        accessorFn: (row) => row.section,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('operation')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => {
          const section = info.getValue();
          return (
            <span>
              {section === 'swap'
                ? t('swap')
                : section === 'spot'
                  ? t('spot')
                  : t('stock')}
            </span>
          );
        },
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
    data: logs?.data ?? [],
    pageCount: logs?.meta?.last_page || 0,
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
        recordCount={logs?.meta.total || 0}
        isLoading={isPending}
        loadingMode="spinner"
        tableLayout={{
          columnsPinnable: true,
          columnsMovable: true,
          columnsVisibility: true,
          cellBorder: true,
        }}
      >
        <Card className="mt-5">
          <CardHeader>
            <CardTitle>{t('transactions')}</CardTitle>
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

export { Logs };
