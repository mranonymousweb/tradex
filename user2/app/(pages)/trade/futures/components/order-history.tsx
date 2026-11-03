'use client';

import { useContext, useMemo, useState } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { localeDate } from '@/helper/formatDate';
import { FuturesContext } from '@/store/futures';
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
import { useLocale } from 'next-intl';
import { Trade, TradeData } from '@/types/futures';
import { getOrderHistory } from '@/services/futures.services';
import { Card, CardFooter, CardTable } from '@/components/ui/card';
import { DataGrid } from '@/components/ui/data-grid';
import { DataGridPagination } from '@/components/ui/data-grid-pagination';
import { DataGridTable } from '@/components/ui/data-grid-table';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';

enum Type {
    MARKET = 1,
    LIMIT = 2,
    STOPMARKET = 3,
    STOPLIMIT = 4,
}

const OrderHistory = () => {
    const lang = useLocale();
    const { pair } = useContext(FuturesContext);
    const [pagination, setPagination] = useState<PaginationState>({
        pageIndex: 0,
        pageSize: 12,
    });

    const url = `pairs=${pair}&page=${pagination.pageIndex + 1}`;
    const { data: history, isPending } = useQuery<TradeData>({
        queryKey: ['history-future', url],
        queryFn: () => getOrderHistory(url),
    });

    const columns = useMemo<ColumnDef<Trade>[]>(
        () => [
            {
                id: 'pairs',
                accessorFn: (row) => row.pairs,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        symbol
                    </span>
                ),
                enableSorting: false,
                cell: (info) => (
                    <div className="flex flex-col items-center">
                        <span>{info.row.original.pairs}</span>
                        <div className="flex space-x-2 mt-1">
                            <span className="bg-secondary text-xs rounded-sm px-1 py-[2px]">
                                Perp
                            </span>
                            <span className="bg-secondary text-xs rounded-sm px-1 py-[2px]">
                                {info.row.original.leverage}X
                            </span>
                        </div>
                    </div>
                ),
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
            {
                id: 'closed_at',
                accessorFn: (row) => row.closed_at,
                header: () => (
                    <span className="flex justify-center text-muted-foreground">
                        time
                    </span>
                ),
                enableSorting: false,
                cell: (info) => localeDate(String(info.getValue()), lang),
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
            {
                id: 'type',
                accessorFn: (row) => row.type,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        type
                    </span>
                ),
                cell: (info) => {
                    const type = info.getValue();
                    return (
                        <span>
                            {type === Type.MARKET
                                ? 'market'
                                : type === Type.LIMIT
                                    ? 'limit'
                                    : type === Type.STOPMARKET
                                        ? 'Stop Market'
                                        : 'Stop Limit'}
                        </span>
                    );
                },
                enableSorting: true,
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
            {
                id: 'pnl',
                accessorFn: (row) => row.side,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        PNL
                    </span>
                ),
                cell: (info) => {
                    const isMinus = Math.sign(+info.row.original?.pnl);
                    return (
                        <div
                            className={`flex flex-col ${isMinus === 1 ? 'text-green' : 'text-destructive'}`}
                        >
                            <span>{decimalPipe(info.row.original.pnl, 2)} USDT</span>
                        </div>
                    );
                },
                enableSorting: false,
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
            {
                id: 'side',
                accessorFn: (row) => row.side,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        Side
                    </span>
                ),
                cell: (info) => (
                    <span
                        className={`${info.row.original.side === 1 ? 'text-green' : 'text-destructive'}`}
                    >
                        {info.row.original.side === 1 ? 'Buy/Long' : 'Sell/Short'}
                    </span>
                ),
                enableSorting: false,
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
            {
                id: 'enter_price',
                accessorFn: (row) => row.enter_price,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        Enter Price
                    </span>
                ),
                cell: (info) => decimalPipe(info.getValue() as string, 4),
                enableSorting: true,
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
            {
                id: 'exit_price',
                accessorFn: (row) => row.exit_price,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        Exit Price
                    </span>
                ),
                cell: (info) => decimalPipe(info.getValue() as string, 4),
                enableSorting: true,
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
            {
                id: 'quantity',
                accessorFn: (row) => row.quantity,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        size
                    </span>
                ),
                cell: (info) => decimalPipe(info.getValue() as string, 4),
                enableSorting: true,
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
            {
                id: 'liq_price',
                accessorFn: (row) => row.liq_price,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        Liq Price
                    </span>
                ),
                cell: (info) => decimalPipe(info.getValue() as string, 4),
                enableSorting: true,
                meta: {
                    cellClassName: 'text-center text-xs text-[#C99400]',
                },
            },
            {
                id: 'fee',
                accessorFn: (row) => row.fee,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        Fee
                    </span>
                ),
                cell: (info) => (
                    <span>{decimalPipe(info.row.original.fee, 4)} USDT</span>
                ),
                enableSorting: true,
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
            {
                id: 'id',
                accessorFn: (row) => row.id,
                header: () => (
                    <span className="flex justify-center text-muted-foreground text-xs pt-2">
                        ID
                    </span>
                ),
                cell: (info) => `#${info.getValue()}`,
                enableSorting: true,
                size: 80,
                meta: {
                    cellClassName: 'text-center text-xs',
                },
            },
        ],
        [lang],
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
