'use client';

import { useState } from 'react';
import { useSearchParams } from 'next/navigation';
import { WalletContext } from '@/store/wallet';
import { CircleDollarSign, Clock, WifiPen } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { PackageType } from '@/types/profile';
import { Currency } from '@/types/wallet';
import {
  HighlightedPosts,
  HighlightedPostsItems,
} from '@/components/partials/common/highlighted-posts';
import { Assets } from './assets';
import { FormAction, Transactions } from './index';

interface Guide {
  url: string | null;
  title: string;
  text: string;
}

interface Props {
  guide: Guide[];
  currencies: Currency[];
  setAllCurrencies: (currencies: Currency[]) => void;
  packageInfo: PackageType;
  googleAuth: boolean;
  search: string;
  setSearch: (s: string) => void;
}

export function WalletContent({
  guide,
  currencies,
  setAllCurrencies,
  packageInfo,
  googleAuth,
  search,
  setSearch,
}: Props) {
  const t = useTranslations('wallet.withdrawal');
  const params = useSearchParams();
  const currencyId = params.get('id');
  const [selectedCoin, setSelectedCoin] = useState<string>(
    currencyId ?? String([currencies[0].id]),
  );
  const posts: HighlightedPostsItems = [
    {
      icon: CircleDollarSign,
      title: guide?.[0].title,
      summary: guide?.[0].text,
      path: guide?.[0].url ?? '#',
    },
    {
      icon: WifiPen,
      title: guide?.[1].title,
      summary: guide?.[1].text,
      path: guide?.[1].url ?? '#',
    },
    {
      icon: Clock,
      title: guide?.[2].title,
      summary: guide?.[2].text,
      path: guide?.[2].url ?? '#',
    },
  ];

  return (
    <WalletContext
      value={{
        selectedCoin,
        setSelectedCoin,
        currencies,
        setAllCurrencies,
        packageInfo,
        googleAuth,
        search,
        setSearch,
      }}
    >
      <div className="grid grid-cols-1 xl:grid-cols-3 gap-y-5 lg:gap-7.5">
        <div className="md:col-span-2">
          <div className="flex flex-col gap-5 lg:gap-7.5">
            <FormAction />
            <Transactions />
          </div>
        </div>
        <div className="col-span-1">
          <div className="flex flex-col gap-5 lg:gap-7.5">
            <Assets title={t('asstes')} />
            <HighlightedPosts posts={posts} />
          </div>
        </div>
      </div>
    </WalletContext>
  );
}
