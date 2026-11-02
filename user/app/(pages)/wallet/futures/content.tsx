'use client';

import { startTransition, useActionState, useEffect, useState } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { useQueryClient } from '@tanstack/react-query';
import { RefreshCw } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { toAbsoluteUrl } from '@/lib/helpers';
import { AlertError } from '@/hooks/alert-error';
import { transferAsset } from '@/services/wallet.services';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { InputWrapper } from '@/components/ui/input';
import InputCurrency from '@/components/ui/input-currency';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinners';
import { Transactions } from './components';

export interface WalletBalance {
  id: number;
  currency_id: number;

  name: string;
  symbol: string;
  icon: string;

  balance: string;
  block_balance: string;
  feature_balance: string;
  margin_balance: string;
  total_balance: string;
}
interface ICompanyProfileItem {
  id: number;
  total: string;
  description: string;
}
type ICompanyProfileItems = Array<ICompanyProfileItem>;

const Content = ({ USDT_ASSETS }: { USDT_ASSETS: WalletBalance }) => {
  const t = useTranslations('futures.Wallet');
  const [amount, setAmount] = useState<string | undefined>('');
  const [select, setSelect] = useState('');
  const [state, action, pending] = useActionState(transferAsset, null);
  const useQuery = useQueryClient();

  useEffect(() => {
    if (state?.success) {
      toast.success(t('success'));
      useQuery.invalidateQueries({ queryKey: ['transactions_wallet_futures'] });
      setAmount('');
    }
  }, [state, useQuery, t]);

  const handleTotalBalance = () => {
    if (select) {
      const maxBalance =
        select === 'balance'
          ? Number(USDT_ASSETS?.balance)
          : Number(USDT_ASSETS?.feature_balance);
      setAmount(String(maxBalance));
    }
  };

  const handleClick = () => {
    if (!amount) {
      toast.error(t('amount_required'));
    }
    if (!select) {
      toast.error(t('select_transfer_required'));
    }
    const formData = {
      symbol: 'USDT',
      amount,
      from: select,
    };
    startTransition(() => action(formData));
  };

  return (
    <>
      <Card className="mb-5">
        <CardContent className="lg:py-7.5">
          {state?.error && <AlertError errors={state.error} />}
          <div className="flex flex-wrap gap-7.5">
            <div className="flex flex-col gap-3 items-center justify-center size-[140px] rounded-xl ring-1 ring-border bg-secondary-clarity">
              <img
                src={toAbsoluteUrl('/media/images/light.webp')}
                className="size-[70px]"
                alt="image"
              />
              <span className="text-sm font-semibold text-mono">
                {t('title')}
              </span>
            </div>
            <div className="flex flex-col gap-5 lg:gap-7.5 grow">
              <div className="flex items-center gap-x-14">
                <div className="flex flex-col">
                  <h2 className="text-sm font-medium text-secondary-foreground mb-1">
                    {t('balance_futures')} (USDT)
                  </h2>
                  <span className="text-lg font-semibold">
                    {decimalPipe(USDT_ASSETS?.feature_balance)}
                  </span>
                </div>
                <div className="flex flex-col">
                  <h2 className="text-sm font-medium text-secondary-foreground mb-1">
                    {t('balance_spot')} (USDT)
                  </h2>
                  <span className="text-lg font-semibold">
                    {decimalPipe(USDT_ASSETS?.balance)}
                  </span>
                </div>
              </div>

              <div className="flex flex-col gap-y-2">
                <div className="flex items-center gap-x-1">
                  <RefreshCw size={18} className="text-sm" />
                  <h2 className="text-sm text-foreground font-semibold">
                    {t('placeholder_transfer')}
                  </h2>
                </div>
                <div className="flex items-center gap-5">
                  <div className="w-64">
                    <Select value={select} onValueChange={setSelect}>
                      <SelectTrigger>
                        <SelectValue placeholder={t('selected')} />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="feature_balance">
                          {t('from_futures_spot')}
                        </SelectItem>
                        <SelectItem value="balance">
                          {t('from_spot_futures')}
                        </SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="w-64">
                    <InputWrapper className="px-0">
                      <InputCurrency
                        type="text"
                        value={amount}
                        onValueChange={(_val) => setAmount(_val)}
                        placeholder={t('amount_required')}
                        className="w-full !border-0 rtl:!border-l-2 ltr:!border-r-2 focus:outline-0 px-2"
                      />
                      <Button
                        type="button"
                        variant="dim"
                        className="font-sans"
                        onClick={handleTotalBalance}
                      >
                        <span>MAX</span>
                      </Button>
                    </InputWrapper>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div className="flex justify-end">
            <Button disabled={pending} onClick={handleClick}>
              {pending ? <Spinner className="size-4 animate-spin" /> : null}
              {t('confirm')}
            </Button>
          </div>
        </CardContent>
      </Card>
      <Transactions />
    </>
  );
};

export { Content, type ICompanyProfileItem, type ICompanyProfileItems };
