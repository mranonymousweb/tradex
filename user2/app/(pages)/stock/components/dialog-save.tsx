/* eslint-disable @typescript-eslint/no-explicit-any */

import { startTransition, useActionState, useEffect, useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { getCompact } from '@/helper/compact';
import { decimalPipe } from '@/helper/decimalPipe';
import { CircleDollarSign, Scale } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { CurrencyStock } from '@/types/stock';
import { AlertError } from '@/hooks/alert-error';
import { createStock } from '@/services/stock.services';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import DialogContent, {
  Dialog,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { InputWrapper } from '@/components/ui/input';
import InputCurrency from '@/components/ui/input-currency';
import { Spinner } from '@/components/ui/spinners';

export const DialogSave = ({
  currency,
  metaLink,
}: {
  currency: CurrencyStock;
  metaLink: string;
}) => {
  const t = useTranslations('stock');
  const [open, setOpen] = useState(false);
  const [input, setInput] = useState('');
  const [dailyProfit, setDailyProfit] = useState(0);
  const [weekProfit, setWeekProfit] = useState(0);
  const [monthProfit, setMonthProfit] = useState(0);
  const [errorValidation, setErrorValidation] = useState<string | null>(null);
  const [acceptRule, setAcceptRule] = useState<string | null>(null);
  const [rule, setRule] = useState(false);
  const [state, action, pending] = useActionState(createStock, null);
  const close = () => setOpen(false);

  useEffect(() => {
    if (state?.success) {
      toast.success(state.message);
      setOpen(false);
    }
  }, [state]);

  const handleChange = (value: any) => {
    const profit = +currency.daily_profit_percent / 100;
    const daily = profit * +value;
    const week = daily * 7;
    const month = daily * 30;
    setDailyProfit(daily);
    setWeekProfit(week);
    setMonthProfit(month);
    setInput(value);
  };

  const handleTotalBalance = () => {
    handleChange(currency.balance);
  };

  const validate = () => {
    let error = null;
    let isValid = false;

    if (Number(input) > Number(currency.balance)) {
      error = t('validate_balance');
    }
    if (Number(input) <= Number(currency.min_amount)) {
      error = t('validate_amount');
    }
    if (Number(input) > Number(currency.max_amount)) {
      error = t('validate_amount_max');
    }
    setErrorValidation(error);
    isValid = true;
    return isValid;
  };

  const handleSubmit = () => {
    const isValid = validate();
    if (!isValid) return;
    if (!rule) {
      setAcceptRule(t('accept_rule'));
      return;
    }

    const data = {
      id: currency.id,
      currency_id: currency.currency.id,
      amount: input,
    };
    startTransition(() => action(data));
  };

  return (
    <>
      <Button
        variant="outline"
        onClick={() => setOpen(true)}
        className="text-primary border-primary mx-2 md:mx-0"
      >
        {t('register')}
      </Button>

      <Dialog open={open} onOpenChange={close}>
        <DialogContent className="w-full h-full md:h-auto md:w-[450px] overflow-y-auto">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-1">
              <div className="flex items-center gap-1">
                <Image
                  src={currency.currency.icon}
                  width={25}
                  height={25}
                  alt="icon"
                />
                <span className="inline-block pt-1">
                  {currency.currency.symbol}
                </span>
              </div>
              <span className="text-xs">({t('interested')})</span>
            </DialogTitle>
          </DialogHeader>
          <AlertError errors={state?.error} />

          <div className="flex mt-3 mb-10 gap-2">
            <span> {t('profit_dilay')} :</span>
            <Badge variant="success" appearance="light">
              %{currency.daily_profit_percent}
            </Badge>
          </div>
          <div className="flex items-center justify-between mb-2">
            <div className="flex items-center gap-1">
              <span className="text-muted-foreground text-2sm">
                {t('balance')} :
              </span>
              <span className="text-2sm">
                {currency.currency.symbol} {decimalPipe(currency.balance, 6)}
              </span>
            </div>
            <Link
              href={`/wallet/deposit?id=${currency.currency.id}`}
              className="flex items-center gap-x-1 text-sm"
            >
              <i className="ki-filled ki-wallet text-primary"></i>
              <span className="text-primary font-light">{t('deposit')}</span>
            </Link>
          </div>
          <InputWrapper
            className={`px-0 ${errorValidation && '!border !border-destructive'}`}
          >
            <InputCurrency
              type="text"
              placeholder={t('input_placeholder')}
              className="w-full !border-0 !border-l-2 focus:outline-0 px-2"
              value={input}
              onValueChange={(val) => handleChange(val)}
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

          {errorValidation && (
            <span className="text-destructive text-xs">{errorValidation}</span>
          )}

          <div className="flex flex-col mt-4">
            <div className="flex items-center gap-x-1">
              <CircleDollarSign className="text-muted-foreground" size={18} />
              <h2 className="text-sm">{t('profit_you')}</h2>
            </div>
            <div className="grid grid-cols-3 gap-3 mt-2">
              <div className="text-sm flex flex-col border rounded-lg items-center gap-y-1 py-2">
                <span className="text-xs text-muted-foreground font-medium">
                  {t('profit_dilay')}
                </span>
                <span>{getCompact(dailyProfit)}</span>
              </div>
              <div className="text-sm flex flex-col border rounded-lg items-center gap-y-1 py-2">
                <span className="text-xs text-muted-foreground font-medium">
                  {t('profit_week')}
                </span>
                <span>{getCompact(weekProfit)}</span>
              </div>
              <div className="text-sm flex flex-col border rounded-lg items-center gap-y-1 py-2">
                <span className="text-xs text-muted-foreground font-medium">
                  {t('profit_month')}
                </span>
                <span>{getCompact(monthProfit)}</span>
              </div>
            </div>
          </div>

          <div className="flex flex-col mt-4">
            <div className="flex items-center gap-x-1">
              <Scale className="text-muted-foreground" size={20} />
              <h2 className="text-sm">{t('interested_rule')}</h2>
            </div>
            <div className="p-2 bg-secondary/35 border rounded-lg text-2sm mt-2">
              <div className="flex items-center justify-between">
                <span>{t('interest_min_amount')}</span>
                <div className="flex gap-1">
                  <span>{currency.currency.symbol}</span>
                  <span>{currency.min_amount}</span>
                </div>
              </div>
              <div className="flex items-center justify-between mt-2">
                <span>{t('interest_max_amount')}</span>
                <div className="flex gap-1">
                  <span>{currency.currency.symbol}</span>
                  <span>{currency.max_amount}</span>
                </div>
              </div>
              <div className="flex items-center justify-between mt-2">
                <span>{t('deposit_profit')}</span>
                <span>{t('daily')}</span>
              </div>
              <div className="flex items-center justify-between mt-2">
                <span>{t('interest_min_day')}</span>
                <span>{currency.min_day}</span>
              </div>
            </div>
          </div>
          <div className="flex items-center space-x-2 mt-3">
            <Checkbox checked={rule} onCheckedChange={() => setRule(!rule)} />
            <Link
              target="_blank"
              href={metaLink ?? '/'}
              className="text-xs text-primary font-medium"
            >
              {t('rule_link')}
              <span className="text-mono mx-1">{t('rule_text')}</span>
            </Link>
          </div>

          {acceptRule && (
            <span className="text-destructive block text-xs mt-1">
              {acceptRule}
            </span>
          )}

          <DialogFooter>
            <div className="flex flex-col-reverse md:flex-row items-center">
              <Button
                type="button"
                variant="outline"
                className="mt-2 md:mt-0 w-full mx-2"
                onClick={close}
              >
                {t('close')}
              </Button>

              <Button
                type="button"
                disabled={pending}
                onClick={handleSubmit}
                className="w-full"
              >
                {pending ? <Spinner className="size-4 animate-spin" /> : null}
                {t('confirm')}
              </Button>
            </div>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
};
