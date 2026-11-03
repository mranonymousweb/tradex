/* eslint-disable @typescript-eslint/no-explicit-any */
'use client';

import { useMemo, useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { decimalPipe, truncateNumber } from '@/helper/decimalPipe';
import CurrencyIcon from '@/public/media/images/currency-icon.webp';
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
import { ArrowBigDownDash, ArrowBigUpDash } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { AssetsRow } from '@/types/wallet';
import { assetsMe } from '@/services/wallet.services';
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
import { cn } from '@/lib/utils';

const TableCurrencies = () => {
    const t = useTranslations('wallet.assets');
    const [pagination, setPagination] = useState<PaginationState>({
        pageIndex: 0,
        pageSize: 12,
    });

    const { data, isPending } = useQuery({
        queryKey: ['asset_me'],
        queryFn: () => assetsMe(),
    });
    const columns = useMemo<ColumnDef<AssetsRow>[]>(
        () => [
            {
                id: 'name',
                accessorFn: (row) => row.id,
                header: () => (
                    <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
                        {t('name_currency')}
                    </span>
                ),
                cell: (info) => (
                    <div className="flex items-center gap-x-1 justify-start">
                        <div className="flex items-center gap-x-1">
                            <Image
                                src={info.row.original.icon ?? CurrencyIcon}
                                width={24}
                                height={24}
                                className="w-6 h-6 rounded-full"
                                alt="icon-currency"
                            />
                            <span className="pt-px">{info.row.original.name}</span>
                        </div>
                        <small className="text-muted-foreground pt-px">
                            ({info.row.original.symbol})
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
                id: 'total_balance',
                accessorFn: (row) => row.total_balance,
                header: () => (
                    <div className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground items-center gap-x-1">
                        <span>{t('total_balance')}</span>
                    </div>
                ),
                cell: (info) => {
                    const decimal =
                        info.row.original.symbol.toUpperCase() === 'IRT' ? 0 : 8;
                    const minusBalance =
                        Number(info.row.original.total_balance) < 0;
                    return (
                        <span className={cn(minusBalance && 'text-red-500')}>
                            {truncateNumber(Number(info.row.original.total_balance), decimal)}
                        </span>
                    );
                },
                enableSorting: false,
                size: 150,
                meta: {
                    cellClassName: '',
                },
            },
            {
                id: 'block_balance',
                accessorFn: (row) => row.block_balance,
                header: () => (
                    <div className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground items-center gap-x-1">
                        <span>{t('asste_block')}</span>
                    </div>
                ),
                enableSorting: false,
                cell: (info) => {
                    const minusBalance =
                        Number(info.row.original.block_balance) < 0;
                    return <span className={cn(minusBalance && 'text-red-500')}>
                        {truncateNumber(Number(info.row.original.block_balance), 8)}
                    </span>
                },
                size: 150,
                meta: {
                    cellClassName: 'text-secondary-foreground',
                },
            },
            {
                id: 'balance',
                accessorFn: (row) => row.balance,
                header: () => (
                    <div className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground items-center gap-x-1">
                        <span>{t('asset_available')}</span>
                    </div>
                ),
                enableSorting: false,
                cell: (info) => {
                    const minusBalance =
                        Number(info.row.original.balance) < 0;
                    return <span className={cn(minusBalance && 'text-red-500')}>
                        {truncateNumber(Number(info.row.original.balance), 8)}
                    </span>
                },
                size: 150,
                meta: {
                    cellClassName: 'text-secondary-foreground',
                },
            },
            {
                id: 'feature_balance',
                accessorFn: (row) => row.feature_balance,
                header: () => (
                    <div className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground items-center gap-x-1">
                        <span>{t('asset_futures')}</span>
                    </div>
                ),
                enableSorting: false,
                cell: (info) => {
                    const minusBalance =
                        Number(info.row.original.feature_balance) < 0;
                    return <span className={cn(minusBalance && 'text-red-500')}>
                        {truncateNumber(Number(info.row.original.feature_balance), 8)}
                    </span>
                },
                size: 150,
                meta: {
                    cellClassName: 'text-secondary-foreground',
                },
            },
            {
                id: 'margin_balance',
                accessorFn: (row) => row.margin_balance,
                header: () => (
                    <div className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground items-center gap-x-1">
                        <span>{t('asset_margin')}</span>
                    </div>
                ),
                enableSorting: false,
                cell: (info) => {
                    const minusBalance =
                        Number(info.row.original.margin_balance) < 0;
                    return <span className={cn(minusBalance && 'text-red-500')}>
                        {truncateNumber(Number(info.row.original.margin_balance), 8)}
                    </span>
                },
                size: 150,
                meta: {
                    cellClassName: 'text-secondary-foreground',
                },
            },
            {
                id: 'id',
                accessorFn: (row) => row.id,
                header: () => (
                    <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground py-3">
                        {t('operation')}
                    </span>
                ),
                enableSorting: false,
                cell: (info) => (
                    <div className="flex items-center space-x-1 justify-start">
                        <Link
                            href={`/wallet/withdrawal?symbol=${info.row.original.symbol}&id=${info.row.original.currency_id}`}
                        >
                            <ArrowBigUpDash
                                strokeWidth={1}
                                color="oklch(57.7% 0.245 27.325)"
                            />
                        </Link>
                        <Link
                            href={`/wallet/deposit?symbol=${info.row.original.symbol}&id=${info.row.original.currency_id}`}
                            className="text-muted-foreground"
                        >
                            <ArrowBigDownDash color="#2EBD85" strokeWidth={1} />
                        </Link>
                    </div>
                ),
                size: 100,
                meta: {
                    cellClassName: '',
                },
            },
        ],
        [t],
    );

    const table = useReactTable({
        columns,
        data: data ?? [],
        pageCount: 1,
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
                recordCount={Number(data?.length)}
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
                <Card>
                    <CardHeader>
                        <CardTitle className="text-secondary-foreground font-normal">
                            {t('title_table')}
                        </CardTitle>
                        <Button variant="outline" className="hidden md:block">
                            <Link href="/swap">{t('swap')}</Link>
                        </Button>
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

export { TableCurrencies };
