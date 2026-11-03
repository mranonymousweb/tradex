/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable react-hooks/exhaustive-deps */

'use client';

import { useEffect, useMemo, useState } from 'react';
import Link from 'next/link';
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
import { ArrowLeft, Copy } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { SubsetRow } from '@/types/marketing';
import { getSubset } from '@/services/marketing.services';
import { Button } from '@/components/ui/button';
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

const Subset = ({ id }: { id: string }) => {
  const t = useTranslations('marketing.table');
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  const { data: subsets, isPending } = useQuery({
    queryKey: ['subset', pagination.pageIndex, id],
    queryFn: () => getSubset(id, pagination.pageIndex + 1),
  });
  const [rowSelection] = useState<RowSelectionState>({});

  const handleCopyText = (value: string) => {
    navigator.clipboard.writeText(value);
    toast.success(t('success_text'));
  };

  const columns = useMemo<ColumnDef<SubsetRow>[]>(
    () => [
      {
        id: 'id',
        accessorFn: (row) => row.id,
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
        id: 'user',
        accessorFn: (row) => row,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('full_name')}
          </span>
        ),
        cell: (info) => (
          <div className="flex items-center justify-start text-foreground font-normal">
            <span>
              {info.row.original.name + ' ' + info.row.original.family}
            </span>
          </div>
        ),
        enableSorting: true,
        size: 150,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'created_at',
        accessorFn: (row) => row.created_at,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('date_register')}
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
        id: 'total_parent_amount',
        accessorFn: (row) => row.total_parent_amount,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('assets_usdt')}
          </span>
        ),
        cell: (info) => {
          return (
            <div className="flex items-center justify-start text-foreground font-normal">
              {decimalPipe(info.row.original.total_parent_amount, 5)}
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
        id: 'code',
        accessorFn: (row) => row.code,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('code_marketing')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <div className="flex items-center justify-start text-foreground font-normal">
            <Button
              variant="dim"
              mode="icon"
              onClick={() =>
                handleCopyText(
                  `${process.env.NEXT_PUBLIC_API_FRONTEND_URL}/signup?ref=${info.row.original.code}`,
                )
              }
            >
              <Copy size={16} />
            </Button>
            <span className="w-40 block truncate">
              {info.row.original.code}
            </span>
          </div>
        ),
        size: 120,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'action',
        accessorFn: (row) => row.id,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('details_transactions')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <Button type="button" size="sm" variant="secondary">
            <Link href={`/marketing/${id}/details/${info.row.original.id}`}>
              <ArrowLeft size={15} className="rtl:rotate-0 ltr:rotate-180" />
            </Link>
          </Button>
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
    data: subsets?.data ?? [],
    pageCount: subsets?.meta?.last_page || 0,
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
        recordCount={subsets?.meta.total || 0}
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
            <CardTitle>{t('title')}</CardTitle>
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

export { Subset };
