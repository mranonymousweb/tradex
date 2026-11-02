'use client';

import { useState } from 'react';
import { swapCurrencies } from '@/helper/swap/list-currency';
import { SwapCurrency } from '@/types/swap';
import { Currencies } from './components/currencies';
import { Swap } from './components/swap/content-swap';
import { PendingCurrency } from './components/swap/pending-currencies-ui';
import { PendingSwap } from './components/swap/pending-swap-ui';

interface Props {
  intiliaCurrencies: SwapCurrency[];
  config: any;
  totalBalance: any;
}

export const ContentSwap = ({
  intiliaCurrencies,
  config,
  totalBalance,
}: Props) => {
  const [allCurrencies, setAllCurrencies] = useState<any[]>(intiliaCurrencies);
  const { currencies } = swapCurrencies(allCurrencies ?? intiliaCurrencies);

  return (
    <div className="grid grid-cols-1 lg:grid-cols-2 gap-x-5 lg:gap-x-7.5 mb-7.5">
      <div>
        {currencies ? (
          <Swap
            setAllCurrencies={setAllCurrencies}
            currencies={currencies}
            config={config}
            totalBalance={totalBalance}
          />
        ) : (
          <PendingSwap />
        )}
      </div>
      <div className="hidden lg:flex">
        {currencies ? (
          <Currencies currencies={currencies} />
        ) : (
          <PendingCurrency />
        )}
      </div>
    </div>
  );
};
