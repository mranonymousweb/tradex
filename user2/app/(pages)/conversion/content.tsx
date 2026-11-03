'use client';

import { useState } from 'react';
import { RefreshCw } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { CurrencyConversion } from '@/types/wallet';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle } from '@/components/ui/card';
import { DialogVerify } from './components/dialog-verify';
import { TableCurrencies } from './components/table-currencies';
import { TableTransactions } from './components/table-transactions';

interface Props {
  currencies: CurrencyConversion[];
}

export const ContentConversion = ({ currencies }: Props) => {
  const t = useTranslations('wallet.conversion');
  const [tab, setTab] = useState(false);
  const buttonEl = (
    <Button variant="outline" className="flex items-center">
      {' '}
      <RefreshCw />
      {t('all_transfrom')}
    </Button>
  );

  return (
    <Card className="min-w-full">
      <CardHeader>
        <CardTitle className="flex justify-between items-center w-full">
          <div className="flex items-center gap-x-2">
            <Button
              variant={`${!tab ? 'primary' : 'secondary'}`}
              onClick={() => setTab(false)}
            >
              {t('currencies')}
            </Button>
            <Button
              variant={`${tab ? 'primary' : 'secondary'}`}
              onClick={() => setTab(true)}
            >
              {t('transactions')}
            </Button>
          </div>
          {currencies?.length > 0 && !tab && (
            <DialogVerify type="all" buttonElement={buttonEl} />
          )}
        </CardTitle>
      </CardHeader>
      {!tab && <TableCurrencies currencies={currencies} />}
      {tab && <TableTransactions />}
    </Card>
  );
};
