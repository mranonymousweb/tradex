/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable react-hooks/exhaustive-deps */

'use client';

import React, { useEffect, useMemo, useState } from 'react';
import { getTicketStatusText } from '@/helper/ticket/getTicketStatusText';
import { keepPreviousData, useQuery } from '@tanstack/react-query';
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
import { Ticket, TicketRow } from '@/types/Ticket';
import { getTickets } from '@/services/ticket.services';
import { Badge, BadgeDot } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
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
import { FilterList } from './filter-list';
import { TicketChat } from './ticket-chat';

const Tickets = () => {
  const t = useTranslations('ticket.table');
  const [isFilter, setIsFilter] = useState(false);
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });
  const [showChat, setShowChat] = useState(false);
  const [ticketId, setTicketId] = useState('');
  const [searchQuery, setSearchQuery] = useState<Ticket[] | null>(null);
  const path = `page=${pagination.pageIndex + 1}&${searchQuery}`;

  const { data, isPending } = useQuery({
    queryKey: ['tickets', pagination.pageIndex + 1, path],
    queryFn: () => getTickets(path),
    placeholderData: keepPreviousData,
  });

  const [rowSelection] = useState<RowSelectionState>({});

  const handleShowChat = (id: string) => {
    setTicketId(id);
    setShowChat(true);
  };

  const columns = useMemo<ColumnDef<TicketRow>[]>(
    () => [
      {
        id: 'id',
        accessorFn: (row) => row.id,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground text-nowrap">
            {t('id_ticket')}
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
        id: 'title',
        accessorFn: (row) => row.title,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('title_ticket')}
          </span>
        ),
        cell: (info) => (
          <div className="flex items-center justify-start text-foreground font-normal">
            <span dir="rtl" className="w-[90%] truncate">
              {info.row.original.title}
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
        id: 'department',
        accessorFn: (row) => row?.department?.name ?? '--',
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground text-nowrap">
            {t('department_ticket')}
          </span>
        ),
        cell: (info) => (
          <div className="flex items-center justify-start text-foreground font-normal">
            {info.row.original?.department?.name ?? '--'}
          </div>
        ),
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
        accessorFn: (row) => row.status,
        header: () => (
          <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
            {t('status')}
          </span>
        ),
        enableSorting: false,
        cell: (info) => (
          <Badge
            variant={
              info.row.original.status === 'new'
                ? 'success'
                : info.row.original.status === 'pending'
                  ? 'warning'
                  : info.row.original.status === 'closed'
                    ? 'destructive'
                    : 'primary'
            }
            size="lg"
            shape="circle"
            appearance="outline"
            className="text-nowrap"
          >
            <BadgeDot />
            {getTicketStatusText(info.row.original.status, t)}
          </Badge>
        ),
        size: 100,
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
        cell: (info) => (
          <Button
            variant="ghost"
            mode="icon"
            onClick={() => handleShowChat(info.row.original.id)}
            className="size-9"
          >
            <div>
              <i className="ki-filled ki-messages text-lg"></i>
            </div>
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
            <CardTitle>{t('tickets')}</CardTitle>
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
      {showChat && (
        <TicketChat
          key={ticketId}
          id={ticketId}
          closeChat={() => setShowChat(false)}
        />
      )}
    </>
  );
};

export { Tickets };
