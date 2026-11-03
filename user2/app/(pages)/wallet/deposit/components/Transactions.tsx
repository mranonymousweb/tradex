'use client';

import { useContext, useEffect, useMemo, useState } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { hideText } from '@/helper/hideText';
import { findCurrency } from '@/helper/wallet/find-currency';
import { WalletContext } from '@/store/wallet';
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
import { Copy } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { walletTransactions } from '@/services/wallet.services';
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
import { TransactionStatus } from '@/types/wallet';
import { formatGatewayLabel } from '@/helper/wallet/format-gateway';
import { DialogDescription } from '../../reports/components/dialog-description';

interface IData {
    id: number; // Use string for ID
    status: number;
    amount: string;
    txt_id?: string;
    created_at: string;
    gateway?: string | { key: string; value: string } | Record<string, string | null>;
    network?: {
        symbol: string;
    };
    currency: {
        symbol: string;
        type: number;
    };
    section: number;
    tag: string;
    description?: string | null;
}

const Transactions = () => {
    const t = useTranslations('wallet.deposit.table');
    const { selectedCoin, currencies } = useContext(WalletContext);
    const [searchQuery, setSearchQuery] = useState(null);
    const [isFilter, setIsFilter] = useState(false);
    const [rowSelection] = useState<RowSelectionState>({});
    const [pagination, setPagination] = useState<PaginationState>({
        pageIndex: 0,
        pageSize: 12,
    });

    const currency = findCurrency(currencies, Number(selectedCoin));
    const type = currency?.type;
    const isIRT = type === 2;
    const isVoucher = type === 3;

    useEffect(() => {
        setPagination((prev) => ({
            ...prev,
            pageIndex: 0,
        }));
    }, [searchQuery, selectedCoin]);

    const path = `type=1&page=${pagination.pageIndex + 1}&currency_id=${selectedCoin}&${searchQuery}`;
    const { data, isPending } = useQuery({
        queryKey: [
            'transactions',
            selectedCoin,
            pagination.pageIndex,
            searchQuery,
            path,
        ],
        queryFn: () => walletTransactions(path),
        refetchInterval: 60000,
    });

    const columns = useMemo<ColumnDef<IData>[]>(
        () => [
            {
                id: 'txt_id',
                accessorFn: (row) => row.txt_id,
                header: () => (
                    <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
                        {isVoucher ? t('voucher') : t('trackig_code')}
                    </span>
                ),
                cell: (info) => {
                    const lengthString = info.row.original.txt_id?.length ?? 0;
                    const txtId =
                        lengthString > 10
                            ? hideText(info.row.original.txt_id, 1, 10)
                            : info.row.original.txt_id;
                    return (
                        <div className="flex items-center justify-start text-foreground font-normal">
                            <Button
                                variant="dim"
                                mode="icon"
                                className="text-muted-foreground hover:text-primary-active"
                                onClick={() =>
                                    navigator.clipboard.writeText(info.row.original.txt_id ?? '')
                                }
                            >
                                <Copy size={16} />
                            </Button>
                            {txtId ?? '------'}
                        </div>
                    );
                },
                enableSorting: false,
                size: 200,
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
                        <small>{info.row.original.currency.symbol}</small>
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
                id: 'section',
                accessorFn: (row) => row.section,
                header: () => (
                    <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
                        {isIRT ? t('type_deposit') : isVoucher ? t('key') : t('network')}
                    </span>
                ),
                cell: (info) =>
                    info.getValue() === 1
                        ? formatGatewayLabel(info.row.original.gateway)
                        : info.getValue() === 14
                            ? t('manual')
                            : info.getValue() === 2
                                ? info.row.original.currency.type === 3
                                    ? hideText(info.row.original.tag, 5, 10)
                                    : info.row.original.network?.symbol
                                : t('card_card'),
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
                size: 100,
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
                cell: (info) => {
                    const status = info.row.original.status;
                    return (
                        <Badge
                            variant={
                                status === TransactionStatus.Confirmed
                                    ? 'success'
                                    : status === TransactionStatus.Reject ||
                                        status === TransactionStatus.Fail
                                        ? 'destructive'
                                        : 'warning'
                            }
                            size="lg"
                            shape="circle"
                            appearance="outline"
                        >
                            <BadgeDot
                                className={
                                    status === TransactionStatus.Confirmed
                                        ? 'success'
                                        : status === TransactionStatus.Reject ||
                                            status === TransactionStatus.Fail
                                            ? 'destructive'
                                            : 'warning'
                                }
                            />
                            <span className="whitespace-nowrap">
                                {status === TransactionStatus.Confirmed
                                    ? t('dialog_filter.status_option.active')
                                    : status === TransactionStatus.Reject
                                        ? t('dialog_filter.status_option.canceled')
                                        : status === TransactionStatus.Fail
                                            ? t('dialog_filter.status_option.failed')
                                            : t('dialog_filter.status_option.waiting')}
                            </span>
                        </Badge>
                    );
                },
                size: 100,
                meta: {
                    cellClassName: '',
                },
            },
            {
                id: 'id',
                accessorFn: (row) => row.id,
                header: () => (
                    <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
                        {t('operation')}
                    </span>
                ),
                cell: (info) => (
                    <DialogDescription
                        status={info.row.original.status}
                        description={info.row.original.description}
                    />
                ),
                enableSorting: false,
                size: 70,
                meta: {
                    cellClassName: '',
                },
            },
        ],
        [isIRT, isVoucher, t],
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
    );
};

export { Transactions };
