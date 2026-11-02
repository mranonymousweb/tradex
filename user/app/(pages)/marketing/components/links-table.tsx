/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable react-hooks/exhaustive-deps */

'use client';

import { useEffect, useMemo, useState } from 'react';
import Link from 'next/link';
import { decimalPipe } from '@/helper/decimalPipe';
import {
  ColumnDef,
  getCoreRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  RowSelectionState,
  useReactTable,
} from '@tanstack/react-table';
import { ArrowLeft, Copy } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { LinkSubsetRow } from '@/types/marketing';
import { Badge, BadgeDot } from '@/components/ui/badge';
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
import { UpdateLink } from './update-link';

const Links = ({
  data,
  isPending,
  setPagination,
  pagination,
}: {
  data: any;
  isPending: boolean;
  setPagination: any;
  pagination: any;
}) => {
  const t = useTranslations('marketing.table');
  const [rowSelection] = useState<RowSelectionState>({});

  const handleCopyText = (value: string) => {
    navigator.clipboard.writeText(value);
    toast.success(t('success_text'));
  };

  const columns = useMemo<ColumnDef<LinkSubsetRow>[]>(
    () => [
      {
        id: 'name',
        accessorFn: (row) => row.name,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('link_name')}
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
        id: 'code',
        accessorFn: (row) => row.code,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('link_marketing')}
          </span>
        ),
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
              {process.env.NEXT_PUBLIC_API_FRONTEND_URL}/signup?ref=$
              {info.row.original.code}
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
        id: 'code',
        accessorFn: (row) => row.code,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('code_marketing')}
          </span>
        ),
        cell: (info) => (
          <div className="flex items-center justify-start text-foreground font-normal">
            <Button
              variant="dim"
              mode="icon"
              onClick={() => handleCopyText(`${info.row.original.code}`)}
            >
              <Copy size={16} />
            </Button>
            <span className="w-40 block truncate">
              {info.row.original.code}
            </span>
          </div>
        ),
        enableSorting: true,
        size: 120,
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
        id: 'user_count',
        accessorFn: (row) => row.user_count,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('subset')}
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
        id: 'is_active',
        accessorFn: (row) => row.is_active,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('status')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => {
          const status = info.getValue();
          return (
            <Badge
              variant={status ? 'success' : 'destructive'}
              size="lg"
              shape="circle"
              appearance="outline"
              className="text-nowrap"
            >
              <BadgeDot />
              {status ? t('active') : t('not_active')}
            </Badge>
          );
        },
        size: 100,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'action',
        accessorFn: (row) => row.section,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('operation')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <div className="flex items-center gap-x-2">
            <UpdateLink item={info.row.original} />
            <Button type="button" size="sm" variant="secondary">
              <Link href={`/marketing/${info.row.original.id}`}>
                <ArrowLeft size={15} className="rtl:rotate-0 ltr:rotate-180" />
              </Link>
            </Button>
          </div>
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
        recordCount={data?.meta.total || 0}
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
            <CardTitle>{t('created_link')}</CardTitle>
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

export { Links };
