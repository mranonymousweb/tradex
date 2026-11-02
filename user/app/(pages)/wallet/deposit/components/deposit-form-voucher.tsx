/* eslint-disable @typescript-eslint/no-explicit-any */

import { CircleAlert } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { UseFormReturn } from 'react-hook-form';
import { Alert } from '@/components/ui/alert';
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { FormSchemaType } from './form-schema';

export const FormVoucher = ({
  form,
}: {
  form: UseFormReturn<FormSchemaType>;
}) => {
  const t = useTranslations('wallet.deposit');

  const handleChange = (field: any, e: React.ChangeEvent<HTMLInputElement>) => {
    form.setValue('type', 'voucher');
    field.onChange(e.target.value);
  };

  return (
    <>
      <FormField
        control={form.control}
        name="txId"
        render={({ field }) => (
          <FormItem className="flex md:flex-row md:items-center">
            <FormLabel className="flex w-full max-w-56">
              {t('voucher')}
            </FormLabel>
            <div className="flex flex-col w-full gap-y-1">
              <FormControl>
                <Input
                  type="text"
                  value={field.value ?? undefined}
                  onChange={(e) => handleChange(field, e)}
                  placeholder={t('enter_voucher')}
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
          <FormItem className="flex md:flex-row md:items-center">
            <FormLabel className="flex w-full max-w-56">
              {t('key')} ({t('optional')})
            </FormLabel>
            <div className="flex flex-col w-full gap-y-1">
              <FormControl>
                <Input
                  type="text"
                  value={field.value ?? undefined}
                  onChange={field.onChange}
                  placeholder={t('enter_key')}
                />
              </FormControl>
              <FormMessage />
              <Alert
                variant="destructive"
                appearance="outline"
                className="flex items-center"
              >
                <CircleAlert />
                <span className="text-xs font-medium">
                  {t('alert_voucher')}
                </span>
              </Alert>
            </div>
          </FormItem>
        )}
      />
    </>
  );
};
