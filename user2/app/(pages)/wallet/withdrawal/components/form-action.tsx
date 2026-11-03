/* eslint-disable @typescript-eslint/no-explicit-any */
'use client';

import React, {
    Suspense,
    useContext,
    useEffect,
    useMemo,
    useRef,
    useState,
} from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { decimalPipe, truncateNumber } from '@/helper/decimalPipe';
import { findCurrency } from '@/helper/wallet/find-currency';
import CurrencyIcon from '@/public/media/images/currency-icon.webp';
import { WalletContext } from '@/store/wallet';
import { zodResolver } from '@hookform/resolvers/zod';
import { cva } from 'class-variance-authority';
import { AlertCircle, Check, ChevronDown, CircleX, Search } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { useForm } from 'react-hook-form';
import { Currency, Network } from '@/types/wallet';
import { cn } from '@/lib/utils';
import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';
import { InputWrapper } from '@/components/ui/input';
import InputCurrency from '@/components/ui/input-currency';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinners';
import ViewList from '../../deposit/components/view-list';
import { useListCurrency } from '../../list-currency';
import { DialogVerify } from './dialog-verify';
import { FormSchema, FormSchemaType } from './form-schema';
import { WithdrawalCurrency } from './withdrawal-currency';
import { WithdrawalRial } from './withdrawal-rial';

const selecetVariants = cva(
    `
    flex w-full bg-background border border-border shadow-xs shadow-black/5 transition-[color,box-shadow] text-foreground placeholder:text-muted-foreground/80 
    focus-visible:ring-ring/30  focus-visible:border-ring focus-visible:outline-none focus-visible:ring-[3px] 
    disabled:cursor-not-allowed disabled:opacity-50 [&[readonly]]:opacity-70 
    file:h-full [&[type=file]]:py-0 file:border-solid file:border-border file:bg-transparent 
    file:font-medium file:not-italic file:text-foreground file:p-0 file:border-0 file:border-e
    aria-invalid:border-destructive/60 aria-invalid:ring-destructive/10 dark:aria-invalid:border-destructive dark:aria-invalid:ring-destructive/20
  `,
    {
        variants: {
            size: {
                lg: 'h-10 px-4 text-sm rounded-md file:pe-4 file:me-4',
                md: 'h-8.5 px-3 text-[0.8125rem] leading-(--text-sm--line-height) rounded-md file:pe-3 file:me-3',
                sm: 'h-7 px-2.5 text-xs rounded-md file:pe-2.5 file:me-2.5',
            },
        },
        defaultVariants: {
            size: 'md',
        },
    },
);

const FormAction = () => {
    const {
        selectedCoin,
        setSelectedCoin,
        currencies,
        packageInfo,
        search,
        setSearch,
    } = useContext(WalletContext);
    const t = useTranslations('wallet.withdrawal');
    const [searchFilter, setSearchFilter] = useState('');
    const [dropDown, setDropDown] = useState(false);
    const [totalAmount, setTotalAmount] = useState<number>(0);
    const [open, setOpen] = useState(false);
    const [formData, setFormData] = useState<any>();
    const contentDropRef = useRef<HTMLDivElement | null>(null);
    const { hasNextPage, isFetchingNextPage, fetchNextPage, isLoading } =
        useListCurrency(search);

    const currency = findCurrency(currencies, Number(selectedCoin));
    const type = currency?.type;
    const isCurrency = type === 1;
    const isIRT = type === 2;
    const decimalFee = isIRT ? 0 : 8;

    const localFiltered = useMemo(() => {
        if (!searchFilter) return currencies;
        const value = searchFilter.trim().toLowerCase();
        return currencies?.filter((item: Currency) => {
            return (
                item.symbol.toLowerCase().includes(value) ||
                item?.name?.toLowerCase().includes(value)
            );
        });
    }, [currencies, searchFilter]);

    useEffect(() => {
        if (localFiltered && localFiltered.length === 0) {
            setSearch(searchFilter);
        }
        if (searchFilter === '') {
            setSearch('');
        }
    }, [searchFilter, localFiltered]);

    const form = useForm<FormSchemaType>({
        resolver: zodResolver(FormSchema(isIRT, isCurrency, t)),
        defaultValues: {
            amount: '',
            currencyId: selectedCoin,
            cardId: '',
            networkId: '',
            address: '',
            tag: '',
        },
    });

    const network = form.watch('networkId');
    const amount = form.watch('amount');

    const filterNetwork = () => {
        if (currency?.symbol === 'IRT') {
            return currency.networks;
        } else {
            if (network) {
                return currency?.networks.find((cl: Network) => cl.id === +network);
            }
        }
    };
    const networks: any = filterNetwork();

    useEffect(() => {
        if (!amount) {
            setTotalAmount(0);
            return;
        }
        if (networks && amount) {
            const value = +amount - Number(networks?.fee);
            const result = value < 0 ? 0 : value;
            setTotalAmount(result);
        }
        if (isIRT && amount) {
            const value = +amount - Number(networks?.fee);
            const result = value < 0 ? 0 : value;
            setTotalAmount(Number(result.toFixed(0)));
        }
    }, [filterNetwork, network, amount, isIRT, currency]);

    const handleSelect = (id: number) => {
        form.setValue('currencyId', String(id));
        setSelectedCoin(id);
        form.resetField('networkId');
        form.resetField('address');
        form.resetField('amount');

        setTotalAmount(0);
        setDropDown(false);
    };

    const handleTotalBalance = () => {
        if (!currency) return;
        const value = truncateNumber(Number(currency?.user_asset?.spot), 6);
        form.setValue('amount', String(value));
    };

    function onSubmit() {
        const values = form.getValues();
        setFormData(values);
        setOpen(true);
    }

    return (
        <Card className="pb-2.5">
            <CardHeader id="webhooks">
                <CardTitle>{t('withdrawal')}</CardTitle>
            </CardHeader>
            <CardContent className="grid gap-x-5">
                <Alert
                    variant="destructive"
                    appearance="light"
                    className={`flex ${packageInfo?.withdrawal && '!hidden'}`}
                >
                    <AlertIcon>
                        <AlertCircle color="oklch(57.7% 0.245 27.325)" />
                    </AlertIcon>
                    <AlertTitle>
                        {t('validate_authentication')}
                        <Link href="/authentication" className="text-primary underline">
                            {t('complete_auth')}
                        </Link>
                    </AlertTitle>
                </Alert>

                <p className="text-sm text-foreground mb-10">{t('info_description')}</p>

                <div className="flex flex-col md:flex-row md:items-center gap-2">
                    <div className="flex w-full max-w-56 text-sm font-medium">
                        {t('selecet_currency')}
                    </div>
                    <div ref={contentDropRef} className="relative w-full">
                        <button
                            className={cn(
                                selecetVariants(),
                                'flex justify-between items-center',
                            )}
                            onClick={() => setDropDown(!dropDown)}
                        >
                            <div className="flex items-center gap-x-1">
                                <Image
                                    src={currency?.icon ?? CurrencyIcon}
                                    width={22}
                                    height={22}
                                    className="w-5 h-5 rounded-full"
                                    alt="icon-currency"
                                />
                                <span className="pt-px">{currency?.symbol}</span>
                            </div>
                            <ChevronDown size={15} className="text-muted-foreground" />
                        </button>

                        <div
                            className={`absolute mt-2 z-10 py-3 bg-popover border shadow-lg rounded-md w-full ${!dropDown && 'hidden'}`}
                        >
                            {/*Search Box*/}
                            <div className="px-2 pb-2 border-b border-border relative">
                                <Search
                                    className="absolute translate-y-[9px] rtl:right-3 ltr:left-3 text-secondary-foreground"
                                    size={15}
                                />
                                <input
                                    type="text"
                                    placeholder={t('placeholder_search')}
                                    className="w-full px-5 placeholder:text-xs h-8 text-sm border rounded-md outline-none focus:ring-1 focus:ring-ring"
                                    value={searchFilter}
                                    onChange={(e) => setSearchFilter(e.target.value)}
                                />
                                {search && (
                                    <button
                                        type="button"
                                        onClick={() => setSearchFilter('')}
                                        className="absolute icon-circlex translate-y-[8px]"
                                    >
                                        <CircleX size={15} />
                                    </button>
                                )}
                            </div>

                            {/*Items*/}
                            <ViewList
                                hasNextPage={hasNextPage}
                                isFetchingNextPage={isFetchingNextPage}
                                fetchNextPage={fetchNextPage}
                                contentDropRef={contentDropRef}
                                setDropDown={setDropDown}
                            >
                                <ul>
                                    {!localFiltered.length && !isLoading ? (
                                        <div className="text-center py-2 text-muted-foreground text-sm">
                                            {t('no_result')}
                                        </div>
                                    ) : (
                                        localFiltered.map((item: Currency) => (
                                            <li
                                                onClick={() => handleSelect(item.id)}
                                                key={item.id}
                                                className="flex items-center justify-between mb-1 p-2 bg-secondary/30 rounded-md hover:bg-secondary"
                                            >
                                                <div className="flex items-center gap-x-1">
                                                    <div className="flex items-center gap-x-1">
                                                        <Image
                                                            src={item.icon ?? CurrencyIcon}
                                                            width={24}
                                                            height={24}
                                                            className="w-6 h-6 rounded-full"
                                                            alt="icon-currency"
                                                        />
                                                        <span className="pt-px text-sm">{item.name}</span>
                                                    </div>
                                                    <small className="text-muted-foreground pt-px text-xs">
                                                        ({item.symbol})
                                                    </small>
                                                </div>
                                                <Check
                                                    className={`text-primary ${+selectedCoin !== item.id && 'hidden'}`}
                                                    size={17}
                                                />
                                            </li>
                                        ))
                                    )}
                                </ul>
                                {(isFetchingNextPage || isLoading) && (
                                    <div className="flex justify-center py-1">
                                        <Spinner className="size-4 animate-spin" />
                                    </div>
                                )}
                            </ViewList>
                        </div>
                    </div>
                </div>

                <Suspense>
                    <Form {...form}>
                        <form
                            onSubmit={form.handleSubmit(onSubmit)}
                            className="block w-full space-y-3 mt-4"
                        >
                            {isIRT && (
                                <WithdrawalRial form={form} filterCurrency={currency} />
                            )}
                            {isCurrency && (
                                <WithdrawalCurrency form={form} filterCurrency={currency} />
                            )}

                            <FormField
                                control={form.control}
                                name="amount"
                                render={({ field }) => (
                                    <FormItem className="flex md:flex-row md:items-center">
                                        <FormLabel className="flex w-full max-w-56">
                                            {t('amount')}
                                            <small className="block pt-px mx-1">
                                                {currency?.symbol ? `(${currency?.symbol})` : ''}
                                            </small>
                                        </FormLabel>
                                        <div className="flex flex-col w-full gap-y-1">
                                            <FormControl>
                                                <InputWrapper
                                                    className={`px-0 ${!packageInfo?.withdrawal && 'opacity-35'}`}
                                                >
                                                    <InputCurrency
                                                        type="text"
                                                        value={field.value}
                                                        onValueChange={field.onChange}
                                                        disabled={!packageInfo?.withdrawal}
                                                        placeholder={t('input_placeholder')}
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
                                            </FormControl>
                                            <FormMessage />

                                            {networks && (
                                                <div className="text-xs flex items-center mt-1 gap-x-2 text-secondary-foreground">
                                                    <span>{t('fee_withdrawal')}:</span>
                                                    <div className="flex items-center gap-x-1">
                                                        <span>
                                                            {decimalPipe(networks?.fee ?? 0, decimalFee)}
                                                        </span>
                                                        <small>{currency?.symbol}</small>
                                                    </div>
                                                </div>
                                            )}
                                        </div>
                                    </FormItem>
                                )}
                            />

                            {/*Final Amount*/}
                            <div className="flex items-center flex-wrap lg:flex-nowrap gap-2.5">
                                <Label className="flex w-full max-w-56 items-center">
                                    {t('received')}
                                    <small className="block pt-1 mx-1">
                                        {currency?.symbol ? `(${currency?.symbol})` : ''}
                                    </small>
                                </Label>
                                <div className="grow flex space-x-1 items-center">
                                    <span className="text-lg font-semibold">
                                        {decimalPipe(totalAmount)}
                                    </span>
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <Button
                                    disabled={!packageInfo?.withdrawal}
                                    variant={packageInfo?.withdrawal ? 'primary' : 'secondary'}
                                >
                                    {t('confirm')}
                                </Button>
                            </div>
                        </form>
                    </Form>
                </Suspense>

                <DialogVerify
                    key={selectedCoin}
                    data={formData}
                    open={open}
                    setOpen={setOpen}
                    form={form}
                    setTotalAmount={setTotalAmount}
                />
            </CardContent>
        </Card>
    );
};

export { FormAction };
