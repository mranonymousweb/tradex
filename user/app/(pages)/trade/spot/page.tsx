import { redirect } from 'next/navigation';
import { createPageMetadata } from '@/lib/page-metadata';
import { TradePair } from '@/types/spot';
import { getProfileInfo } from '@/services/profile.services';
import { listFavoriteSpot, marketCurrencySpot } from '@/services/spot.services';
import RootPage from './components/root';

export async function generateMetadata() {
  return createPageMetadata('/trade/spot');
}

export default async function Page({
  searchParams,
}: {
  searchParams: Promise<{ [date: string]: string }>;
}) {
  const pair = (await searchParams).market ?? 'BTC_USDT';
  const basePair = pair.split('_')[1];

  const [user, tradePair, listFavorite] = await Promise.all([
    getProfileInfo(),
    marketCurrencySpot(),
    listFavoriteSpot(),
  ]);

  const has_pair = tradePair?.some(
    (pair: TradePair) => pair.baseCurrency.symbol === basePair,
  );
  if (!has_pair) {
    redirect('/');
  }

  return (
    <RootPage
      key={pair}
      pair={pair}
      userId={user?.id}
      userPackage={user?.package}
      tradePair={tradePair}
      listFavorite={listFavorite}
    />
  );
}
