/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable react-hooks/exhaustive-deps */

'use client';

import { useEffect, useMemo, useState } from 'react';
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
import { bankRow } from '@/types/bank';
import { listBanks } from '@/services/banks.services';
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
import { Operation } from './dialog-operation';
import { StatusBnak } from './status';

const Table = () => {
  const t = useTranslations('bank.table');
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  const { data, isPending } = useQuery({
    queryKey: ['card-banks'],
    queryFn: () => listBanks(pagination.pageIndex + 1),
  });

  const [rowSelection] = useState<RowSelectionState>({});
  const columns = useMemo<ColumnDef<bankRow>[]>(
    () => [
      {
        id: 'id',
        accessorFn: (row) => row.id,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('id')}
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
        id: 'bank',
        accessorFn: (row) => row.bank.name,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('bank_name')}
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
        id: 'card_number',
        accessorFn: (row) => row.card_number,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('card_number')}
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
        id: 'iban',
        accessorFn: (row) => row.iban,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('shaba_number')}
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
        id: 'currency',
        accessorFn: (row) => row.currency.symbol,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('name_currency')}
          </span>
        ),
        cell: (info) => info.getValue(),
        enableSorting: true,
        size: 70,
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
        cell: (info) => <StatusBnak info={info.row.original.status} />,
        size: 100,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'operation',
        accessorFn: (row) => row.status,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('operation')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <Operation
            description={info.row.original.description}
            id={info.row.original.id}
            status={info.row.original.status}
          />
        ),
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
            <CardTitle>{t('tilte')}</CardTitle>
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
