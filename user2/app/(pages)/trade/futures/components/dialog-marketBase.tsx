import { useContext, useState } from 'react';
import { futuresCurrencies } from '@/helper/futures/currencyPrice';
import { FuturesContext } from '@/store/futures';
import { Search, Star } from 'lucide-react';
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
import ViewListItem from '../../spot/components/view-list-item';
import { ListItems } from './list-items';

export const DialogMarketBase = () => {
    const { pair, tradePair, listFavorite } = useContext(FuturesContext);
    const [isActiveTab, setIsActiveTab] = useState('USDT');
    const [search, setSearch] = useState('');
    const [openDialog, setOpenDialog] = useState(false);
    const [isFocus, setISFocus] = useState<boolean>(false);
    const {
        currencies,
        isLoading,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
    } = futuresCurrencies(isActiveTab, search, listFavorite);

    const closeDialog = () => {
        setOpenDialog(false);
    };

    const favorites = currencies?.filter((cur) => cur.favorite === true);
    const listCurrency = isActiveTab === '' ? favorites : currencies;

    const handleListBaseMarket = (item: TradePair) => {
        return (
            <span
                key={item.id}
                className={`
                    pb-2 cursor-pointer hover:text-mono 
                    ${isActiveTab === item.baseCurrency.symbol && 'active_tab'}`}
                onClick={() => setIsActiveTab(item.baseCurrency.symbol)}
            >
                {item.baseCurrency.symbol}
            </span>
        );
    };

    return (
        <>
            <div
                className="flex items-center space-x-2 cursor-pointer p-3 hover:text-primary"
                onClick={() => setOpenDialog(true)}
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

            <Dialog open={openDialog} onOpenChange={closeDialog}>
                <DialogContent className="w-full md:w-[800px] px-0">
                    <DialogHeader className="px-4">
                        <DialogTitle>Market base</DialogTitle>
                    </DialogHeader>
                    <DialogDescription></DialogDescription>
                    <div className="flex flex-col" dir="ltr">
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
                                    placeholder="search ..."
                                    className="w-full h-full focus:outline-0 rounded-lg"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    onFocus={() => setISFocus(true)}
                                    onBlur={() => setISFocus(false)}
                                />
                            </label>
                        </div>
                        <div className="flex items-center space-x-3 text-muted-foreground mt-4 text-sm border-b px-4">
                            <span
                                className={`
                                    pb-2 cursor-pointer hover:text-mono 
                                    ${isActiveTab === '' && 'active_tab'}`}
                                onClick={() => setIsActiveTab('')}
                            >
                                Favorites
                            </span>
                            {tradePair?.map((item: TradePair) => handleListBaseMarket(item))}
                        </div>
                        <div className="flex flex-col mt-6">
                            <div className="flex items-center text-2sm text-muted-foreground mb-2 px-4">
                                <h4 className="w-2/5">Symbols</h4>
                                <h4 className="w-1/3 text-end">Last Price</h4>
                                <h4 className="w-1/3 text-end">24h Changes</h4>
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
                                        <ListItems key={i} item={item} setOpen={setOpenDialog} />
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
