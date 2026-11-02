import { useContext, useState } from 'react';
import { currenciesPrice } from '@/helper/spot/currencyPrice';
import { MainSpotContext } from '@/store/trade-spot';
import { Search, Star } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { TradePair } from '@/types/spot';
import { DataLoading } from '@/components/ui/data-loading';
import DialogContent, {
  Dialog,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { NoData } from '@/components/ui/no-data';
import { Spinner } from '@/components/ui/spinners';
import { ListItems } from './list-items';
import { TogglePair } from './toggle-pair';
import ViewListItem from './view-list-item';

export const DialogMarketBase = () => {
  const t = useTranslations('spot');
  const { pair, tradePair, listFavorite } = useContext(MainSpotContext);
  const [search, setSearch] = useState('');
  const [open, setOpen] = useState(false);
  const [isFocus, setISFocus] = useState<boolean>(false);

  const basePair = pair.split('_')[1];
  const [isActiveTab, setIsActiveTab] = useState<string>(basePair);

  const {
    currencies,
    isLoading,
    fetchNextPage,
    hasNextPage,
    isFetchingNextPage,
  } = currenciesPrice(pair, isActiveTab, search, listFavorite);

  const closeDialog = () => {
    setOpen(false);
  };

  const favorites = currencies?.filter((cur) => cur.favorite === true);
  const listCurrency = isActiveTab === '' ? favorites : currencies;

  return (
    <>
      <div
        className="flex items-center space-x-2 cursor-pointer p-3 hover:text-primary"
        onClick={() => setOpen(true)}
      >
        <div className="border p-1 rounded-lg hidden lg:block">
          <Star fill="#1379f0" size={17} strokeWidth={0} />
        </div>
        <h3 className="font-semibold uppercase">{pair.replace(/_/g, '')}</h3>
        <svg
          fill="IconNormal"
          className="w-4 h-4 text-secondary-foreground"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M16.5 8.49v2.25L12 15.51l-4.5-4.77V8.49h9z"
            fill="currentColor"
          ></path>
        </svg>
      </div>

      <Dialog open={open} onOpenChange={closeDialog}>
        <DialogContent className="w-full md:w-[900px] px-0">
          <DialogHeader className="px-4">
            <DialogTitle>{t('market_base')}</DialogTitle>
          </DialogHeader>
          <DialogDescription></DialogDescription>
          <div className="flex flex-col" dir="ltr">
            {/*Search bar*/}
            <div className="px-4">
              <label
                htmlFor="searchInput"
                className={`
                                    flex items-center border w-full h-10 rounded-lg  
                                    ${isFocus && 'border-primary'}`}
              >
                <Search color="#a1a1aa" className="mx-1" />
                <input
                  type="text"
                  id="searchInput"
                  placeholder={t('search')}
                  className="w-full h-full focus:outline-0 rounded-lg"
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  onFocus={() => setISFocus(true)}
                  onBlur={() => setISFocus(false)}
                />
              </label>
            </div>

            {/*Tabs*/}
            <div className="flex items-center overflow-x-auto space-x-3 text-muted-foreground mt-4 text-sm border-b px-4">
              <span
                className={`
                                    pb-2 cursor-pointer hover:text-mono 
                                    ${isActiveTab === '' && 'active_tab'}`}
                onClick={() => setIsActiveTab('')}
              >
                {t('favorites')}
              </span>
              {tradePair?.map((item: TradePair, i) => (
                <TogglePair
                  key={i}
                  item={item}
                  isActiveTab={isActiveTab}
                  setIsActiveTab={setIsActiveTab}
                />
              ))}
            </div>

            {/*Lists*/}
            <div className="flex flex-col mt-6">
              <div className="flex items-center text-2sm text-muted-foreground mb-2 px-4">
                <h4 className="w-2/5">{t('symbols')}</h4>
                <h4 className="w-1/3 text-end">{t('last_price')}</h4>
                <h4 className="w-1/3 text-end">24h {t('changes')}</h4>
              </div>
              <ViewListItem
                fetchNextPage={fetchNextPage}
                hasNextPage={hasNextPage}
                isFetchingNextPage={isFetchingNextPage}
              >
                {isLoading ? (
                  <div className="mt-10">
                    <DataLoading />
                  </div>
                ) : listCurrency?.length === 0 ? (
                  <div className="flex items-center justify-center h-full">
                    <NoData />
                  </div>
                ) : (
                  listCurrency?.map((item, i) => (
                    <ListItems key={i} item={item} setOpen={setOpen} />
                  ))
                )}
                <li>
                  {isFetchingNextPage && (
                    <div className="flex justify-center py-1">
                      <Spinner className="size-4 animate-spin" />
                    </div>
                  )}
                </li>
              </ViewListItem>
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </>
  );
};
