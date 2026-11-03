'use client';

import { useEffect, useMemo, useState } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { hideText } from '@/helper/hideText';
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
import { Copy, Eye } from 'lucide-react';
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
import { TransactionStatus } from '@/types/wallet';
import { DialogDescription } from './dialog-description';
import { FilterList } from './filter-list';

interface IData {
    id: number; // Use string for ID
    status: TransactionStatus;
    description?: string | null;
    amount: string;
    txt_id?: string;
    created_at: string;
    gateway?: string | { key: string; value: string } | Record<string, string | null>;
    network?: {
        symbol: string;
    };
    currency: {
        symbol: string;
    };
    section: number;
    type: number;
}

function formatGatewayLabel(gateway: IData['gateway']): string {
    if (!gateway) return '------';
    if (typeof gateway === 'string') return gateway;

    if (typeof gateway === 'object' && !Array.isArray(gateway)) {
        if (typeof gateway.key === 'string') return gateway.key;

        if (typeof gateway.value === 'string') {
            const gatewayId = gateway.value;
            const label = (gateway as Record<string, string | null>)[gatewayId];
            return typeof label === 'string' ? label : gatewayId;
        }

        const labels = Object.entries(gateway).filter(
            ([, value]) => typeof value === 'string' && value.length > 0,
        ) as [string, string][];

        if (labels.length === 1) return labels[0][1];
    }

    return '------';
}

const Transactions = () => {
    const t = useTranslations('wallet.deposit.table');
    const [searchQuery, setSearchQuery] = useState(null);
    const [isFilter, setIsFilter] = useState(false);
    const [rowSelection] = useState<RowSelectionState>({});
    const [pagination, setPagination] = useState<PaginationState>({
        pageIndex: 0,
        pageSize: 12,
    });

    useEffect(() => {
        setPagination((prev) => ({
            ...prev,
            pageIndex: 0,
        }));
    }, [searchQuery]);

    const path = `page=${pagination.pageIndex + 1}&${searchQuery}`;
    const { data, isPending } = useQuery({
        queryKey: ['transactions', pagination.pageIndex, searchQuery, path],
        queryFn: () => walletTransactions(path),
    });
    console.log(data);
    const columns = useMemo<ColumnDef<IData>[]>(
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
                enableSorting: true,
                size: 100,
                meta: {
                    cellClassName: '',
                },
            },
            {
                id: 'txt_id',
                accessorFn: (row) => row.txt_id,
                header: () => (
                    <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
                        {t('trackig_code')}
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
                id: 'currency',
                accessorFn: (row) => row.currency.symbol,
                header: () => (
                    <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
                        {t('currency')}
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
                id: 'type',
                accessorFn: (row) => row.type,
                header: () => (
                    <span className="flex justify-start font-normal text-[0.8125rem] text-accent-foreground">
                        {t('type_transaction')}
                    </span>
                ),
                cell: (info) => {
                    const type = info.row.original.type;
                    return <span>{type === 1 ? t('deposit') : t('withdrawal')}</span>;
                },
                enableSorting: true,
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
                    <div className="flex justify-start gap-x-1 font-normal text-[0.8125rem] text-accent-foreground">
                        <span>{t('gateWay_name')}</span>/<span> {t('network')}</span>
                    </div>
                ),
                cell: (info) => {
                    const section = info.getValue<number>();
                    if (section === 1) return formatGatewayLabel(info.row.original.gateway);
                    if (section === 2) return info.row.original.network?.symbol ?? '------';
                    if (section === 10) return 'Api';
                    return t('card_card');
                },
                enableSorting: true,
                size: 130,
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
                size: 150,
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
