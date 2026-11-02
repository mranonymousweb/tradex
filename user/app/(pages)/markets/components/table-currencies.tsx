/* eslint-disable @typescript-eslint/no-explicit-any */
'use client';

import { useCallback, useEffect, useMemo, useState } from 'react';
import { marketsCurrencies } from '@/helper/markets/markets';
import {
  getCoreRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  PaginationState,
  RowSelectionState,
  useReactTable,
} from '@tanstack/react-table';
import { Search } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { MarketCurrency } from '@/types/market-currency';
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

const TableCurrencies = ({ columns }: { columns: any }) => {
  const t = useTranslations('markets');
  const [search, setSearch] = useState('');
  const [isIRT, setIsIRT] = useState(false);

  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });

  useEffect(() => {
    setPagination((prev) => ({
      ...prev,
      pageIndex: 0,
    }));
  }, [search]);

  const path = `page=${pagination.pageIndex + 1} ${search && `&symbol=${search.toUpperCase()}`}`;
  const { currencies, data } = marketsCurrencies(path);
  const [rowSelection] = useState<RowSelectionState>({});

  useMemo(() => {
    const isIRT = data?.data?.some((currency: MarketCurrency) =>
      currency?.symbol?.includes('IRT'),
    );
    setIsIRT(isIRT);
  }, []);

  const columnVisibility = useMemo(
    () => ({
      fiat: isIRT,
    }),
    [isIRT],
  );

  useEffect(() => {
    setPagination((prev) => ({
      ...prev,
      pageIndex: 0,
    }));
  }, [search]);

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
    data: currencies ?? [],
    pageCount: data?.meta?.last_page || 0,
    getRowId: (row: any) => String(row?.id),
    state: {
      pagination,
      columnVisibility,
    },
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
        isLoading={!currencies}
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
            <CardTitle className="text-secondary-foreground font-normal hidden md:block">
              {t('title_card')}
            </CardTitle>
            <Button variant="outline" className="w-full md:w-auto">
              <Search size={16} />
              <input
                type="text"
                placeholder={t('search')}
                className="border-0 focus:outline-0 w-full"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
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
