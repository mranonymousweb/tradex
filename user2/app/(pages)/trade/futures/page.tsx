import {
    getMarketBase,
    listFavoriteFutures,
} from '@/services/futures.services';
import { getProfileInfo } from '@/services/profile.services';
import { createPageMetadata } from '@/lib/page-metadata';
import RootPage from './components/root';

export const dynamic = 'force-dynamic';

export async function generateMetadata() {
  return createPageMetadata('/trade/futures');
}

export default async function Page() {
    const [user, tradePair, listFavorite] = await Promise.all([
        getProfileInfo(),
        getMarketBase(),
        listFavoriteFutures(),
    ]);

    return (
        <RootPage
            userId={user?.id}
            tradePair={tradePair}
            listFavorite={listFavorite}
        />
    );
}
