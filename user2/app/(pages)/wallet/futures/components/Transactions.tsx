'use client';

import { useEffect, useMemo, useState } from 'react';
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
  RowSelectionState,
  useReactTable,
} from '@tanstack/react-table';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { Currency } from '@/types/market-currency';
import { historyTransactionFutures } from '@/services/wallet.services';
import { Badge, BadgeDot } from '@/components/ui/badge';
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

interface IData {
  id: number; // Use string for ID
  status: string;
  amount: string;
  created_at: string;
  gateway: string;
  type: string;
  currency: Currency;
  description: string;
}

const Transactions = () => {
  const t = useTranslations('wallet.deposit.table');
  const [rowSelection] = useState<RowSelectionState>({});
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  const { data, isPending } = useQuery({
    queryKey: ['transactions_wallet_futures', pagination.pageIndex],
    queryFn: () => historyTransactionFutures(pagination.pageIndex + 1),
  });

  const columns = useMemo<ColumnDef<IData>[]>(
    () => [
      {
        id: 'currency',
        accessorFn: (row) => row.currency,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('currency')}
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
        size: 200,
        meta: {
          cellClassName: '',
        },
      },
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
        id: 'amount',
        accessorFn: (row) => row.amount,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('amount')}
          </span>
        ),
        cell: (info) => (
          <span className="flex gap-x-1 items-center">
            {decimalPipe(info.row.original.amount)}
          </span>
        ),
        enableSorting: true,
        size: 130,
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
        cell: () => (
          <Badge
            variant="success"
            size="lg"
            shape="circle"
            appearance="outline"
          >
            <BadgeDot className="success" />
            {t('dialog_filter.status_option.active')}
          </Badge>
        ),
        enableSorting: true,
        size: 130,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'description',
        accessorFn: (row) => row.description,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('type_transfer')}
          </span>
        ),
        cell: (info) => {
          const title = info.getValue();
          return (
            <span>
              {title === 'from_feature_balance_to_balance'
                ? t('from_futures_spot')
                : t('from_spot_futures')}
            </span>
          );
        },
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
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('date')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => info.getValue(),
        size: 150,
        meta: {
          cellClassName: '',
        },
      },
    ],
    [t],
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
    getRowId: (row: IData) => String(row.id),
    state: { pagination },
    onPaginationChange: setPagination,
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    manualPagination: true,
  });

  return (
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
      <Card>
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
  );
};

export { Transactions };
