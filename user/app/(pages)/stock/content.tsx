'use client';

import { useState } from 'react';
import Link from 'next/link';
import { Wallet } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { CurrencyStock } from '@/types/stock';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle } from '@/components/ui/card';
import { TableCurrencies } from './components/table-currencies';
import { TableMyStock } from './components/table-stock';

interface Props {
  currencies: CurrencyStock[];
  metaLink: string;
}

export const ContentStock = ({ metaLink, currencies }: Props) => {
  const t = useTranslations('stock');
  const [tab, setTab] = useState(false);

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex justify-between items-center w-full">
          <div className="flex items-center gap-x-2">
            <Button
              variant={`${!tab ? 'primary' : 'secondary'}`}
              onClick={() => setTab(false)}
            >
              {t('interest')}
            </Button>
            <Button
              variant={`${tab ? 'primary' : 'secondary'}`}
              onClick={() => setTab(true)}
            >
              {t('interest_me')}
            </Button>
          </div>
          <Link href="/wallet/deposit">
            <Button
              type="button"
              variant="outline"
              className="hidden md:flex items-center"
            >
              <Wallet />
              {t('deposit_currency')}
            </Button>
          </Link>
        </CardTitle>
      </CardHeader>
      {!tab && <TableCurrencies currencies={currencies} metaLink={metaLink} />}
      {tab && <TableMyStock />}
    </Card>
  );
};
