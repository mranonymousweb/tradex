'use client';

import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { metaData } from '@/services/metaData.services';
import { getProfileInfo } from '@/services/profile.services';
import { DataLoading } from '@/components/ui/data-loading';
import { Container } from '@/components/common/container';
import { useListCurrency } from '../../list-currency';
import { WalletContent } from './content';

export const WalletContentWrapper = () => {
  const [search, setSearch] = useState('');

  const { data: guide, isLoading: guideLoading } = useQuery({
    queryKey: ['meta'],
    queryFn: metaData,
  });

  const { data: profile, isLoading: profileLoading } = useQuery({
    queryKey: ['profile'],
    queryFn: getProfileInfo,
  });

  const { currencies, setAllCurrencies } = useListCurrency(search);

  const isLoading = guideLoading || profileLoading || !currencies.length;

  if (isLoading) {
    return <DataLoading />;
  }

  return (
    <Container>
      <WalletContent
        guide={guide?.rules?.deposit}
        currencies={currencies}
        setAllCurrencies={setAllCurrencies}
        packageInfo={profile?.package}
        plugins={guide?.plugins}
        search={search}
        setSearch={setSearch}
      />
    </Container>
  );
};
