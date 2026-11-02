/* eslint-disable @typescript-eslint/no-explicit-any */
import { useContext } from 'react';
import Image from 'next/image';
import { decimalPipe } from '@/helper/decimalPipe';
import { WalletContext } from '@/store/wallet';
import { useQuery } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { UseFormReturn } from 'react-hook-form';
import { bankRow, StatusBank } from '@/types/bank';
import { listBanks } from '@/services/banks.services';
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinners';
import { FormSchemaType } from './form-schema';

interface Props {
  filterCurrency: any;
  form: UseFormReturn<FormSchemaType>;
}

export const WithdrawalRial: React.FC<Props> = ({ filterCurrency, form }) => {
  const t = useTranslations('wallet.withdrawal');
  const { packageInfo } = useContext(WalletContext);
  const currencyId = form.watch('currencyId');

  const { data, isPending } = useQuery({
    queryKey: ['card-banks'],
    queryFn: () => listBanks(),
  });

  const activeBanks = data
    ? data?.data.filter(
        (bank: bankRow) =>
          bank.status === StatusBank.ACTIVE &&
          Number(bank.currency.id) === Number(currencyId),
      )
    : [];

  return (
    <FormField
      control={form.control}
      name="cardId"
      render={({ field }) => (
        <FormItem className="flex md:flex-row md:items-center">
          <FormLabel className="flex w-full max-w-56">
            {t('choose_bank')}
          </FormLabel>
          <div className="flex flex-col w-full gap-y-1">
            <FormControl>
              <Select
                disabled={!packageInfo?.withdrawal}
                value={field.value ?? undefined}
                onValueChange={field.onChange}
              >
                <SelectTrigger>
                  <SelectValue placeholder={t('choose')} />
                </SelectTrigger>
                <SelectContent>
                  {isPending ? (
                    <div className="flex justify-center py-3">
                      <Spinner className="size-4 animate-spin" />
                    </div>
                  ) : (
                    activeBanks.map((item: any) => (
                      <SelectItem
                        key={item.id}
                        slot={`${item.name}`}
                        value={`${item.id}`}
                      >
                        <div className="flex space-x-2">
                          <Image
                            src={item.bank.icon}
                            width={20}
                            height={20}
                            className="w-5 h-5 rounded-full"
                            alt="icon-currency"
                          />
                          <div className="flex items-center flex-wrap space-x-2">
                            <span>{item.bank.name}</span>
                            <span className="text-xs font-medium text-muted-foreground">
                              ({item.iban})
                            </span>
                          </div>
                        </div>
                      </SelectItem>
                    ))
                  )}
                </SelectContent>
              </Select>
            </FormControl>
            <FormMessage />
            {
              <div className="flex flex-col md:flex-row justify-between">
                <div className="mt-1 flex items-center gap-x-1.5 text-[#FF6F1E]">
                  <span className="text-2xs">{t('min_withdrawal')}:</span>
                  <div className="flex items-center text-xs gap-x-1">
                    <span>
                      {filterCurrency
                        ? decimalPipe(filterCurrency?.networks.min_withdraw, 0)
                        : 0}
                    </span>
                    <small className="text-2xs">{filterCurrency?.symbol}</small>
                  </div>
                </div>
                <div className="mt-1 flex items-center gap-x-1.5 text-[#FF6F1E]">
                  <span className="text-2xs">{t('max_withdrawal')}:</span>
                  <div className="flex items-center text-xs gap-x-1">
                    <span>
                      {filterCurrency
                        ? decimalPipe(filterCurrency?.networks?.max_withdraw, 0)
                        : 0}
                    </span>
                    <small className="text-2xs">{filterCurrency?.symbol}</small>
                  </div>
                </div>
              </div>
            }
          </div>
        </FormItem>
      )}
    />
  );
};
