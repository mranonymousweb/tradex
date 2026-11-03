'use client';

import { startTransition, Suspense, useActionState, useEffect } from 'react';
import Image from 'next/image';
import { zodResolver } from '@hookform/resolvers/zod';
import { useQueryClient } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import { StatusSaturn, TypeBank, TypeCurrencyBank } from '@/types/bank';
import { AlertError } from '@/hooks/alert-error';
import { addCardBank } from '@/services/banks.services';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import {
  Form,
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
  SelectContentWithSearch,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinners';
import { FormSchema, FormSchemaType } from './form-schema';

interface Props {
  cardCurrencies: TypeCurrencyBank[];
  banks: TypeBank[];
}

export default function FormAdd({ cardCurrencies, banks }: Props) {
  const t = useTranslations('bank');
  const [state, action, pending] = useActionState(addCardBank, null);
  const queryClient = useQueryClient();

  const form = useForm<FormSchemaType>({
    resolver: zodResolver(FormSchema(t)),
    defaultValues: {
      bank_id: '',
      currency_id: '',
      card_number: '',
      iban: '',
    },
  });

  useEffect(() => {
    if (state?.success) {
      toast.success(state.message);
      queryClient.invalidateQueries({
        queryKey: ['card-banks'],
      });
      form.reset();
    }
  }, [state, queryClient, form]);

  const selectedCurrency = form.watch('currency_id');
  const getSaturnVerification = selectedCurrency
    ? cardCurrencies.find((card) => card.id === +selectedCurrency)
    : null;
  const isActiveAuth =
    getSaturnVerification?.saturn_verification === StatusSaturn.ACTIVE;

  function onSubmit() {
    const values = form.getValues();
    startTransition(() => action(values));
  }

  return (
    <Card className="w-full my-5">
      <CardContent className="p-6">
        {state?.error && <AlertError errors={state.error} />}

        <Suspense>
          <Form {...form}>
            <form
              onSubmit={form.handleSubmit(onSubmit)}
              className="block w-full space-y-5 mt-4"
            >
              <div className="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                <FormField
                  control={form.control}
                  name="currency_id"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>{t('choose_currency')}</FormLabel>
                      <FormControl>
                        <Select
                          value={field.value}
                          onValueChange={field.onChange}
                        >
                          <SelectTrigger>
                            <SelectValue
                              placeholder={t('placeholder_select')}
                            />
                          </SelectTrigger>
                          <SelectContent>
                            {cardCurrencies ? (
                              cardCurrencies.map((currency) => (
                                <SelectItem
                                  key={currency.id}
                                  value={`${currency.id}`}
                                >
                                  {currency.symbol}
                                </SelectItem>
                              ))
                            ) : (
                              <span className="text-xs">{t('no_result')}</span>
                            )}
                          </SelectContent>
                        </Select>
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                ></FormField>

                <FormField
                  control={form.control}
                  name="bank_id"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>{t('choose_bank')}</FormLabel>
                      <FormControl>
                        <Select
                          value={field.value}
                          onValueChange={field.onChange}
                        >
                          <SelectTrigger>
                            <SelectValue
                              placeholder={t('placeholder_select')}
                            />
                          </SelectTrigger>
                          <SelectContentWithSearch selected={field.onChange}>
                            <SelectGroup>
                              {banks ? (
                                banks.map((bank) => (
                                  <SelectItem
                                    key={bank.id}
                                    slot={bank.name}
                                    value={`${bank.id}`}
                                  >
                                    <div className="flex items-center gap-x-2">
                                      <Image
                                        src={bank.icon}
                                        width={24}
                                        height={24}
                                        className="w-6 h-6 rounded-full"
                                        alt="icon-currency"
                                      />
                                      {bank.name}
                                    </div>
                                  </SelectItem>
                                ))
                              ) : (
                                <span className="text-xs">
                                  {t('no_result')}
                                </span>
                              )}
                            </SelectGroup>
                          </SelectContentWithSearch>
                        </Select>
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />

                <FormField
                  control={form.control}
                  name="card_number"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>{t('card_number')}</FormLabel>
                      <FormControl>
                        <Input placeholder={t('placeholder_card')} {...field} />
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />
                {!isActiveAuth && (
                  <FormField
                    control={form.control}
                    name="iban"
                    render={({ field }) => (
                      <FormItem>
                        <FormLabel>{t('shaba_number')}</FormLabel>
                        <FormControl>
                          <Input
                            placeholder={t('placeholder_shaba')}
                            {...field}
                          />
                        </FormControl>
                        <FormMessage />
                      </FormItem>
                    )}
                  />
                )}
              </div>

              <CardFooter className="flex justify-end px-0 items-center">
                <Button type="submit" disabled={pending}>
                  {pending ? <Spinner className="size-4 animate-spin" /> : null}
                  {t('confirm')}
                </Button>
              </CardFooter>
            </form>
          </Form>
        </Suspense>
      </CardContent>
    </Card>
  );
}
