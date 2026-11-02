'use client';

import Image from 'next/image';
import { decimalPipe } from '@/helper/decimalPipe';
import DefaultIcon from '@/public/media/images/currency-icon.webp';
import { useTranslations } from 'next-intl';
import { SwapCurrency } from '@/types/swap';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { HexagonBadge } from './hexagon-badge';

interface Props {
  currencies: SwapCurrency[];
}

const Currencies = ({ currencies }: Props) => {
  const t = useTranslations('swap');
  const listItems = currencies
    ?.filter(
      (currency) => currency.symbol !== 'IRT' && currency.symbol !== 'USDT',
    )
    ?.slice(0, 4);

  const changePercent = (value: number) => {
    const isMinus = Math.sign(value);
    const classElement = isMinus === -1 ? ' text-destructive' : 'text-green';
    const plus = isMinus === 1 && '+';

    return (
      <span className={`text-2sm ${classElement}`} dir="ltr">
        {plus}
        {decimalPipe(value, 2)}%
      </span>
    );
  };

  return (
    <Card className="grow">
      <CardHeader>
        <CardTitle>{t('popular')}</CardTitle>
      </CardHeader>
      <CardContent className="flex flex-col gap-y-3 min-h-96">
        {listItems.map((item, index) => (
          <div key={index} className="flex flex-col gap-5">
            <div
              className={`flex items-center justify-between flex-wrap border 
                                border-border rounded-xl gap-2 px-4 py-4 bg-secondary-clarity`}
            >
              <div className="flex flex-col items-start">
                <div className="flex items-center">
                  <small className="mx-1 text-secondary-foreground text-xs">
                    USDT
                  </small>
                  <span>{decimalPipe(item.last, item.currency.decimal)}</span>
                </div>
                {changePercent(item.changePercent)}
              </div>
              <div className="flex items-center gap-3.5">
                <div className="flex flex-col items-end">
                  <p className="text-sm font-medium text-mono">
                    {item.currency.symbol}
                  </p>
                  <p className="text-sm text-secondary-foreground">
                    {item.currency.name}
                  </p>
                </div>
                <HexagonBadge
                  stroke="stroke-gray-200 dark:stroke-gray-950"
                  fill=""
                  size="size-[50px]"
                  badge={
                    <Image
                      src={item.currency.icon ?? DefaultIcon}
                      width={24}
                      height={24}
                      className="rounded-full w-6 h-6"
                      alt="coin"
                    />
                  }
                />
              </div>
            </div>
          </div>
        ))}
      </CardContent>
    </Card>
  );
};

export { Currencies };
