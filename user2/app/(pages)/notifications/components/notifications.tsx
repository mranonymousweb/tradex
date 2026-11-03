/* eslint-disable @typescript-eslint/no-explicit-any */
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
import { NotificationRow, NotificationsType } from '@/types/notification';
import { getNotifications } from '@/services/notification.services';
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
import { UpdateActions } from './update-action';

const Notifications = () => {
  const t = useTranslations('notification.table');
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });
  const { data: notifications, isPending } = useQuery<NotificationsType>({
    queryKey: ['notifications', pagination.pageIndex],
    queryFn: () =>
      getNotifications(pagination.pageIndex + 1, '&section=normal'),
  });

  const [rowSelection] = useState<RowSelectionState>({});
  const columns = useMemo<ColumnDef<NotificationRow>[]>(
    () => [
      {
        id: 'id',
        accessorFn: (row) => row.id,
        header: () => (
          <span className="flex justify-start font-normal whitespace-nowrap text-[0.8125rem] text-accent-foreground">
            {t('number_notif')}
          </span>
        ),
        cell: (info) => (
          <div className="flex items-center justify-start text-foreground font-normal">
            <span dir="rtl" className="w-[90%] truncate">
              {info.row.original.id}
            </span>
          </div>
        ),
        enableSorting: true,
        size: 80,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'title',
        accessorFn: (row) => row.title,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('title')}
          </span>
        ),
        cell: (info) => (
          <div className="flex items-center justify-start text-foreground font-normal">
            <span dir="rtl" className="w-[90%] truncate">
              {info.row.original.title}
            </span>
          </div>
        ),
        enableSorting: false,
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
            {t('create_at')}
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
        accessorFn: (row) => row.read,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('status')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <Badge
            variant={!info.row.original.read ? 'destructive' : 'success'}
            size="lg"
            shape="circle"
            appearance="outline"
            className="text-nowrap"
          >
            <BadgeDot />
            {info.row.original.read
              ? t('status_option.read')
              : t('status_option.un_read')}
          </Badge>
        ),
        size: 150,
        meta: {
          cellClassName: '',
        },
      },
      {
        id: 'action',
        accessorFn: (row) => row.id,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('operation')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => {
          const notification = info.row.original;
          return (
            <div className="flex justify-start">
              <UpdateActions notification={notification} />
            </div>
          );
        },
        size: 70,
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
    data: notifications?.data ?? [],
    pageCount: notifications?.meta?.last_page || 0,
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
        recordCount={notifications?.meta?.total || 0}
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
            <CardTitle>{t('title_header')}</CardTitle>
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

export { Notifications };
