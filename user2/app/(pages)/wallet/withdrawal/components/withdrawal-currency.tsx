/* eslint-disable @typescript-eslint/no-explicit-any */
import { useContext } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { WalletContext } from '@/store/wallet';
import { useTranslations } from 'next-intl';
import { UseFormReturn } from 'react-hook-form';
import { Network } from '@/types/wallet';
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { FormSchemaType } from './form-schema';

export const WithdrawalCurrency = ({
  form,
  filterCurrency,
}: {
  form: UseFormReturn<FormSchemaType>;
  filterCurrency: any;
}) => {
  const t = useTranslations('wallet.withdrawal');
  const { packageInfo } = useContext(WalletContext);
  const network = form.watch('networkId');

  const filterNetwork = network
    ? filterCurrency?.networks.find((cl: Network) => cl.id === +network)
    : undefined;

  return (
    <>
      <FormField
        control={form.control}
        name="networkId"
        render={({ field }) => (
          <FormItem className="flex md:flex-row md:items-center">
            <FormLabel className="flex w-full max-w-56">
              {t('select_network')}
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
                    {filterCurrency ? (
                      filterCurrency.networks.filter((network: Network) => network.is_withdraw_able).map((network: Network) => (
                        <SelectItem key={network.id} value={`${network.id}`}>
                          {network.symbol}
                        </SelectItem>
                      ))
                    ) : (
                      <span className="text-xs">{t('no_result')}</span>
                    )}
                  </SelectContent>
                </Select>
              </FormControl>
              <FormMessage />

              {field.value && (
                <div className="flex flex-col md:flex-row justify-between">
                  <div className="mt-1 flex items-center gap-x-1.5 text-[#FF6F1E]">
                    <span className="text-2xs">{t('min_withdrawal')}:</span>
                    <div className="flex items-center text-xs gap-x-1">
                      <span>
                        {filterNetwork
                          ? decimalPipe(filterNetwork?.min_withdraw, 6)
                          : 0}
                      </span>
                      <small className="text-2xs">
                        {filterCurrency?.symbol}
                      </small>
                    </div>
                  </div>
                  <div className="mt-1 flex items-center gap-x-1.5 text-[#FF6F1E]">
                    <span className="text-2xs">{t('max_withdrawal')}:</span>
                    <div className="flex items-center text-xs gap-x-1">
                      <span>
                        {filterNetwork
                          ? decimalPipe(filterNetwork?.max_withdraw, 6)
                          : 0}
                      </span>
                      <small className="text-2xs">
                        {filterCurrency?.symbol}
                      </small>
                    </div>
                  </div>
                </div>
              )}
            </div>
          </FormItem>
        )}
      />

      <FormField
        control={form.control}
        name="address"
        render={({ field }) => (
          <FormItem className="flex md:flex-row md:items-center">
            <FormLabel className="flex w-full max-w-56">
              {t('address_wallet')}
            </FormLabel>
            <div className="flex flex-col w-full gap-y-1">
              <FormControl>
                <Input
                  type="text"
                  value={field.value}
                  onChange={field.onChange}
                  disabled={!packageInfo?.withdrawal}
                  placeholder={t('address_placeholder')}
                />
              </FormControl>
              <FormMessage />
            </div>
          </FormItem>
        )}
      />

      <FormField
        control={form.control}
        name="tag"
        render={({ field }) => (
          <FormItem
            className={`flex md:flex-row md:items-center ${!filterNetwork?.has_tag && '!hidden'}`}
          >
            <FormLabel className="flex w-full max-w-56">{t('tag')}</FormLabel>
            <div className="flex flex-col w-full gap-y-1">
              <FormControl>
                <Input
                  type="text"
                  value={field.value}
                  onChange={field.onChange}
                  disabled={!packageInfo?.withdrawal}
                  placeholder={t('enter_tag')}
                />
              </FormControl>
              <FormMessage />
            </div>
          </FormItem>
        )}
      />
    </>
  );
};
