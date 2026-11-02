'use client';

import { useState } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { SwapContext } from '@/store/swap';
import { useTranslations } from 'next-intl';
import { Config, SwapCurrency, TransferType } from '@/types/swap';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { CardResult } from './card-result';
import {
  DECIMAL_CURRENCY,
  DECIMAL_FEE,
  DECIMAL_FINAL,
  DECIMAL_PAYMNET,
  DECIMAL_RECEIVE,
} from './config';
import { ButtonAction } from './dialog-confirm';
import { ReverseCurrency } from './reverse-currency';
import { SectionPayment } from './section-payment';
import { SectionReceive } from './section-receive';

interface Props {
  setAllCurrencies: React.Dispatch<React.SetStateAction<SwapCurrency[]>>;
  currencies: SwapCurrency[];
  config: Config;
  totalBalance: string;
}

const Swap: React.FC<Props> = ({
  setAllCurrencies,
  currencies,
  config,
  totalBalance,
}) => {
  const t = useTranslations('swap');
  const [inputPayment, setInputPayment] = useState('');
  const [changeInput, setChangeInput] = useState<TransferType>(
    TransferType.NULL,
  );
  const [inputReceive, setInputReceive] = useState('');
  const [selectedPayment, setSelectedPayment] = useState(currencies[0]);
  const [selectedReceive, setSelectedReceive] = useState(currencies[1]);

  const decimaPay =
    selectedPayment.currency.symbol === 'IRT' ? 0 : DECIMAL_PAYMNET;
  const decimaReceive =
    selectedReceive.currency.symbol === 'IRT' ? 0 : DECIMAL_RECEIVE;
  const decimaCurrency =
    selectedReceive.currency.symbol === 'IRT' ? 0 : DECIMAL_CURRENCY;
  const decimaFee = selectedReceive.currency.symbol === 'IRT' ? 0 : DECIMAL_FEE;
  const decimaFinal =
    selectedReceive.currency.symbol === 'IRT' ? 0 : DECIMAL_FINAL;

  return (
    <SwapContext
      value={{
        setAllCurrencies,
        currencies,
        config,
        inputPayment,
        inputReceive,
        setInputPayment,
        setInputReceive,
        selectedPayment,
        setSelectedPayment,
        selectedReceive,
        setSelectedReceive,
        changeInput,
        setChangeInput,
        decimaPay,
        decimaReceive,
        decimaCurrency,
        decimaFee,
        decimaFinal,
      }}
    >
      <Card className="grow">
        <CardHeader>
          <CardTitle>{t('total_assets')}</CardTitle>
          <CardTitle>USDT {decimalPipe(totalBalance, 4)}</CardTitle>
        </CardHeader>

        <CardContent className="pt-4 pb-3 p-0 m-4 flex flex-col gap-y-4 min-h-96">
          <div className="flex flex-col gap-y-2 relative">
            <SectionPayment />
            <SectionReceive />

            {/*Icon Reverse*/}
            <ReverseCurrency />
          </div>

          {/* Fee && Total */}
          <CardResult />

          {/*Button Register*/}
          <ButtonAction />
        </CardContent>
      </Card>
    </SwapContext>
  );
};

export { Swap };
