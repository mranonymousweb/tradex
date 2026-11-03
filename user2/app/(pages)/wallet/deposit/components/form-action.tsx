/* eslint-disable react-hooks/exhaustive-deps */
'use client';

import React, {
    startTransition,
    useActionState,
    useContext,
    useEffect,
    useMemo,
    useRef,
    useState,
} from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { findCurrency } from '@/helper/wallet/find-currency';
import CurrencyIcon from '@/public/media/images/currency-icon.webp';
import { WalletContext } from '@/store/wallet';
import { zodResolver } from '@hookform/resolvers/zod';
import { useQueryClient } from '@tanstack/react-query';
import { cva } from 'class-variance-authority';
import { AlertCircle, Check, ChevronDown, CircleX, Search } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import { Currency, WalletRecive } from '@/types/wallet';
import { cn } from '@/lib/utils';
import { AlertError } from '@/hooks/alert-error';
import { deposit } from '@/services/wallet.services';
import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Form } from '@/components/ui/form';
import { Spinner } from '@/components/ui/spinners';
import { useListCurrency } from '../../list-currency';
import { ButtonAction } from './button-action';
import { DepositCurrency } from './deposit-currency';
import { FormVoucher } from './deposit-form-voucher';
import { DepositRial } from './deposit-rial';
import { FormSchema, FormSchemaType } from './form-schema';
import ViewList from './view-list';
import { useRouter } from 'next/navigation';

const selecetVariants = cva(
    `
    flex w-full bg-input border border-border shadow-xs shadow-black/5 transition-[color,box-shadow] text-foreground placeholder:text-muted-foreground/80 
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
    const t = useTranslations('wallet.deposit');
    const [searchFilter, setSearchFilter] = useState('');
    const [dropDown, setDropDown] = useState(false);
    const [autoCard, setAutoCard] = useState(false);
    const [address, setAddress] = useState<WalletRecive | null>(null);
    const [state, action, pending] = useActionState(deposit, null);
    const currency = findCurrency(currencies, Number(selectedCoin));
    const type = currency?.type;
    const isCurrency = type === 1;
    const isIRT = type === 2;
    const isVoucher = type === 3;

    const queryClient = useQueryClient();
    const contentDropRef = useRef<HTMLDivElement | null>(null);
    const { hasNextPage, isFetchingNextPage, fetchNextPage, isLoading } =
        useListCurrency(search);
    const router = useRouter();

    useEffect(() => {
        if (state?.success) {
            if (state.isGateWay) {
                router.push(state.url);
                return;
            }
            toast.success(state.message);

            queryClient.invalidateQueries({
                queryKey: ['transactions'],
            });
            form.reset();
            setAddress(null);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [state]);

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
            networkId: '',
            currencyId: selectedCoin,
            txId: '',
            type: '',
            transaction: '',
            amount: '',
            file: '',
            tag: '',
            currencyType: type,
        },
    });

    const handleSelect = (id: number) => {
        form.setValue('currencyId', String(id));
        setSelectedCoin(id);
        form.resetField('networkId');
        form.resetField('txId');
        setAddress(null);
        setDropDown(false);
    };

    function onSubmit() {
        const values = form.getValues();
        if (isIRT && values.type === 'card') {
            if (!values.file) {
                toast.error(t('bank.validate_upload_file'));
                return;
            }
        }
        if (
            currency?.networks?.min_deposit === 0 ||
            currency?.networks?.max_deposit === 0
        ) {
            toast.error(t('validation.minmum_maximum'));
            return;
        }
        const data = {
            ...values,
            currencyType: type,
        };
        startTransition(() => action(data));
    }

    return (
        <Card className="pb-2.5">
            <CardHeader id="webhooks">
                <CardTitle>{t('deposit')}</CardTitle>
            </CardHeader>
            <div className="p-2">
                <AlertError errors={state?.error} />
            </div>

            <CardContent className="grid gap-x-5">
                <Alert
                    variant="destructive"
                    appearance="light"
                    className={`flex ${packageInfo?.deposit && '!hidden'}`}
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

                {/*Select Currency::Start*/}
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
                            className={`absolute mt-2 z-10 py-3 bg-popover border shadow-lg 
                            rounded-md w-full ${!dropDown && 'hidden'}`}
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
                                {searchFilter && (
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
                {/*Select Currency:End*/}

                <Form {...form}>
                    <form
                        onSubmit={form.handleSubmit(onSubmit)}
                        className="block w-full space-y-3 mt-4"
                    >
                        {isIRT && (
                            <DepositRial
                                currency={currency}
                                form={form}
                                setAutoCard={setAutoCard}
                            />
                        )}
                        {isCurrency && (
                            <DepositCurrency
                                filterCurrency={currency}
                                form={form}
                                address={address}
                                setAddress={setAddress}
                            />
                        )}
                        {isVoucher && <FormVoucher form={form} />}

                        <ButtonAction
                            address={address}
                            autoCard={autoCard}
                            packageInfo={packageInfo}
                            pending={pending}
                        />
                    </form>
                </Form>
            </CardContent>
        </Card>
    );
};

export { FormAction };
