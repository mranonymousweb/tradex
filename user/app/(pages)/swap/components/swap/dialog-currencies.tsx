/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable react-hooks/exhaustive-deps */
import { memo, useDeferredValue, useMemo, useState } from 'react';
import { swapListCurrency } from '@/helper/swap/list-currency';
import { Search } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { DataLoading } from '@/components/ui/data-loading';
import DialogContent, {
  Dialog,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { NoData } from '@/components/ui/no-data';
import { Spinner } from '@/components/ui/spinners';
import { ListItems } from './dialog-items';
import ViewListItem from './view-list-item';

type Props = {
  open: boolean;
  setOpen: React.Dispatch<React.SetStateAction<boolean>>;
  selected: (item: any) => void;
};

const DialogCurrencies = ({ selected, open, setOpen }: Props) => {
  const t = useTranslations('swap.dialog');
  const [isFocus, setISFocus] = useState<boolean>(false);
  const [search, setSearch] = useState('');
  const deferredValue = useDeferredValue(search);

  const {
    isLoading,
    currencies,
    hasNextPage,
    isFetchingNextPage,
    fetchNextPage,
  } = swapListCurrency(deferredValue);

  const closeDialog = () => {
    setOpen(false);
  };

  const localFiltered = useMemo(() => {
    if (!search) return currencies;
    const value = search.trim().toLowerCase();
    return currencies?.filter((item: any) => {
      return (
        item?.currency?.symbol.toLowerCase().includes(value) ||
        item?.currency?.name?.toLowerCase().includes(value)
      );
    });
  }, [currencies, search]);

  return (
    <>
      <Dialog open={open} onOpenChange={closeDialog}>
        <DialogContent variant="Xlarge">
          <DialogHeader className="px-4">
            <DialogTitle>{t('list_currency')}</DialogTitle>
          </DialogHeader>
          <DialogDescription></DialogDescription>
          <div className="flex flex-col">
            {/*Search bar*/}
            <div className="md:px-4">
              <label
                htmlFor=""
                className={`flex items-center border w-full h-10 rounded-lg  ${isFocus && 'border-primary'}`}
              >
                <Search color="#a1a1aa" className="mx-1" />
                <input
                  type="text"
                  placeholder={t('search')}
                  className="w-full h-full focus:outline-0 rounded-lg"
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  onFocus={() => setISFocus(true)}
                  onBlur={() => setISFocus(false)}
                />
              </label>
            </div>
            <div className="flex flex-col mt-6">
              <div className="flex justify-between items-center text-2sm text-muted-foreground font-medium mb-2 md:px-4">
                <h4 className="md:w-2/5">{t('name_currency')}</h4>
                <div className="w-3/5 flex justify-between">
                  <h4 className="hidden md:block">{t('balance')}</h4>
                  <h4 className="md:rtl:pr-10 md:ltr:pl-10">
                    {t('price')}
                    <small className="mx-1">(USDT)</small>
                  </h4>
                  <h4>{t('changes')}</h4>
                </div>
              </div>

              <ViewListItem
                hasNextPage={hasNextPage}
                fetchNextPage={fetchNextPage}
                isFetchingNextPage={isFetchingNextPage}
              >
                {isLoading ? (
                  <li className="h-full flex items-center justify-center">
                    <DataLoading />
                  </li>
                ) : localFiltered?.length === 0 ? (
                  <li className="h-full flex items-center justify-center">
                    <NoData />
                  </li>
                ) : (
                  localFiltered?.map((item, i) => (
                    <ListItems
                      key={i}
                      item={item}
                      selected={selected}
                      closeDialog={closeDialog}
                    />
                  ))
                )}
              </ViewListItem>
              {isFetchingNextPage && (
                <div className="flex justify-center py-1">
                  <Spinner className="size-4 animate-spin" />
                </div>
              )}
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </>
  );
};
export default memo(DialogCurrencies);
