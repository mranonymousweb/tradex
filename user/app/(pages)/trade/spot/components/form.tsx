'use client';

import {
    memo,
    startTransition,
    useActionState,
    useContext,
    useEffect,
    useMemo,
    useState,
} from 'react';
import Link from 'next/link';
import { decimalPipe } from '@/helper/decimalPipe';
import { calculateOrder } from '@/helper/spot/calculateOrder';
import { MainSpotContext } from '@/store/trade-spot';
import { zodResolver } from '@hookform/resolvers/zod';
import { useQueryClient } from '@tanstack/react-query';
import {
    ChevronDown,
    CircleAlert,
    EqualApproximately,
    Wallet,
} from 'lucide-react';
import { useLocale, useTranslations } from 'next-intl';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import { setData } from '@/lib/storage';
import { AlertError } from '@/hooks/alert-error';
import { createOrder } from '@/services/spot.services';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import InputCurrency from '@/components/ui/input-currency';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinners';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { FormType, MarketAmountType, OrderSide } from './constants';
import { InputComponent } from './input-component';
import { FormSchema, FormSchemaType } from './schema';
import { Slider } from './Slider';
import { Tabs } from './tabs';
import { DotsLoader } from '@/components/ui/dots-loader';

interface Props {
    buttomSheet: boolean;
    switchTab: boolean;
    setSwitchTab: (tab: boolean) => void;
    isBalanceLoading: boolean;
}

const marketOrderTypes = [FormType.Market, FormType.StopMarket] as const;
type MarketOrderFormType = (typeof marketOrderTypes)[number];

const isMarketOrderType = (value: number): value is MarketOrderFormType =>
    marketOrderTypes.includes(value as MarketOrderFormType);

const FormCreateSpot = ({
    buttomSheet,
    switchTab,
    setSwitchTab,
    isBalanceLoading,
}: Props) => {
    const [state, action, pending] = useActionState(createOrder, null);
    const [type, setType] = useState<FormType>(FormType.Limit);
    const [typeAmountByFormType, setTypeAmountByFormType] = useState<
        Record<MarketOrderFormType, MarketAmountType>
    >({
        [FormType.Market]: MarketAmountType.Quote,
        [FormType.StopMarket]: MarketAmountType.Quote,
    });

    const handleSwitchTab = (value: boolean) => {
        setData('switch-tab-spot', value);
        setSwitchTab(value);
    };
    const side = switchTab ? OrderSide.Sell : OrderSide.Buy;
    const lang = useLocale();
    const isRtl = lang === 'fa';
    const dir = isRtl ? 'rtl' : 'ltr';


    const t = useTranslations('spot');
    const {
        pair,
        price,
        userPackage,
        balance,
        isFiat,
        USD_IRR,
        userId,
        totalOrders,
    } = useContext(MainSpotContext);

    const queryClient = useQueryClient();

    const baseSymbol = pair.split('_')[0];
    const basePair = pair.split('_')[1];

    const currencyBalance =
        side === OrderSide.Buy
            ? Number(balance?.base_balance ?? 0)
            : Number(balance?.main_balance ?? 0);

    const balanceSymbol = side === OrderSide.Buy ? basePair : baseSymbol;
    const hasBalance = currencyBalance > 0;
    const currencySymbol = baseSymbol;

    const form = useForm<FormSchemaType>({
        resolver: zodResolver(FormSchema(t)),
        defaultValues: {
            base_currency: basePair,
            main_currency: baseSymbol,
            side,
            type,
            price: '',
            amount: '',
            stop_price: '',
            type_amount: MarketAmountType.Quote,
        },
    });

    const amount = form.watch('amount');
    const inputPrice = form.watch('price');
    const typeOrder = form.watch('type');

    const isStopOrder = [FormType.StopMarket, FormType.StopLimit].includes(
        +typeOrder,
    );
    const isMarketOrder = isMarketOrderType(+typeOrder);


    const marketOrderType = isMarketOrder
        ? (+typeOrder as MarketOrderFormType)
        : null;
    const effectiveTypeAmount = marketOrderType
        ? typeAmountByFormType[marketOrderType]
        : MarketAmountType.Quote;

    useEffect(() => {
        if (state?.success) {
            queryClient.invalidateQueries({ queryKey: ['spot-balance'] });
            queryClient.invalidateQueries({ queryKey: ['order-market'] });
            queryClient.invalidateQueries({ queryKey: ['history'] });
            toast.success(t('message_success'));
            form.resetField('amount');
        } else if (state?.error) {
            toast.error(state.message);
        }
    }, [state, queryClient, form, t]);

    useEffect(() => {
        form.setValue('side', side);
    }, [side, form]);

    useEffect(() => {
        form.setValue('type', type);
        form.resetField('amount');
        form.resetField('stop_price');
        form.setValue('price', String(price));
    }, [type, form]);

    useEffect(() => {
        if (price && !inputPrice) {
            form.setValue('price', String(price));
        }
        form.setValue('main_currency', baseSymbol);
        form.setValue('base_currency', basePair);
    }, [price, baseSymbol, basePair, form]);

    useEffect(() => {
        if (!totalOrders) return;

        form.setValue('price', String(totalOrders.price));
        form.setValue('amount', String(totalOrders.amount));
    }, [totalOrders, form]);

    const calculated = useMemo(
        () =>
            calculateOrder({
                price: Number(isMarketOrder ? price : inputPrice),
                amount: Number(amount),
                userPackage,
                hasFiat: isFiat,
                USD_IRR,
                side,
            }),
        [
            inputPrice,
            amount,
            userPackage,
            isFiat,
            USD_IRR,
            side,
            isMarketOrder,
            price,
        ],
    );

    const finalAmount = useMemo(() => {
        const received = +calculated.received;
        const priceNum = Number(isMarketOrder ? price : inputPrice);

        if (side === OrderSide.Buy) {
            return effectiveTypeAmount === MarketAmountType.Base
                ? received / priceNum
                : received;
        }

        return effectiveTypeAmount === MarketAmountType.Base
            ? received
            : received * priceNum;
    }, [
        side,
        effectiveTypeAmount,
        calculated.received,
        inputPrice,
        isMarketOrder,
        price,
    ]);

    const resultOrder = useMemo(
        () => [
            {
                id: 2,
                icon: true,
                title: t('fee'),
                symbol: '',
                value: calculated.feeAmount,
                isActive: true,
                isFee: true,
            },
            {
                id: 3,
                icon: true,
                title: t('final_amount'),
                symbol: side === OrderSide.Buy ? baseSymbol : basePair,
                value: finalAmount,
                isActive: true,
                isFee: false,
            },
        ],
        [t, basePair, baseSymbol, calculated, isMarketOrder, side, finalAmount],
    );

    const handleChangeTypeAmount = (value: MarketAmountType) => {
        if (!marketOrderType) return;

        setTypeAmountByFormType((prev) => ({
            ...prev,
            [marketOrderType]: value,
        }));
        form.setValue('amount', '');
    };

    const amountSymbol =
        isMarketOrder && effectiveTypeAmount === MarketAmountType.Base
            ? basePair
            : currencySymbol;

    const onSubmit = () => {
        if (!hasBalance) {
            toast.error(t('required_balance'));
            return;
        }

        const values = form.getValues();
        const amount = effectiveTypeAmount === MarketAmountType.Base
            ? Number(values.amount) / Number(price)
            : Number(values.amount);

        startTransition(() =>
            action({
                base_currency: values.base_currency,
                main_currency: values.main_currency,
                side: values.side,
                type: values.type,
                price: isMarketOrder ? price : Number(values.price),
                amount,
                ...(values.stop_price
                    ? { stop_price: Number(values.stop_price) }
                    : {}),
            }),
        );
    };

    return (
        <div
            className={`form-actions bg-card w-full p-3 rounded-lg shadow-xs h-full relative
            ${buttomSheet && 'show'}`}
            dir="ltr"
        >
            {isBalanceLoading && (
                <div className="absolute inset-0 z-10 flex items-center justify-center rounded-lg bg-card/80">
                    <DotsLoader />
                </div>
            )}
            <label htmlFor="switchTabSpot">
                <input
                    type="checkbox"
                    id="switchTabSpot"
                    className="hidden"
                    checked={switchTab}
                    onChange={(e) => handleSwitchTab(e.target.checked)}
                />

                <div
                    className={`
                        switch-tab--spot relative flex flex-1 items-center h-8 
                        justify-between bg-secondary p-1 rounded-md cursor-pointer
                    `}
                >
                    <span className="w-full text-2sm font-medium flex justify-center z-[1]">
                        {t('buy')}
                    </span>
                    <span className="w-full text-2sm font-medium flex justify-center z-[1]">
                        {t('sell')}
                    </span>
                </div>
            </label>

            <div className="flex items-center justify-between mt-6">
                <Tabs tab={type} setTab={setType} />

                <TooltipProvider>
                    <Tooltip delayDuration={50}>
                        <TooltipTrigger className="cursor-pointer">
                            <CircleAlert
                                size={17}
                                fill="#9a9c9f"
                                color="white"
                            />
                        </TooltipTrigger>
                        <TooltipContent className="text-xs w-64">
                            {t('guide_spot')}
                        </TooltipContent>
                    </Tooltip>
                </TooltipProvider>
            </div>

            <Form {...form}>
                <form onSubmit={form.handleSubmit(onSubmit)}>
                    <div className="flex items-center justify-between my-3">
                        <div className="text-xs flex items-center text-muted-foreground gap-1">
                            <small>{t('avble')}</small>
                            <b className="text-foreground">
                                {decimalPipe(
                                    currencyBalance,
                                    side === OrderSide.Buy ? 2 : 6,
                                )}
                            </b>
                            <small>{balanceSymbol}</small>
                        </div>

                        <Link href="/wallet/deposit">
                            <Wallet size={15} color="#9f9fa9" />
                        </Link>
                    </div>

                    <div className="space-y-3">
                        {isStopOrder && (
                            <FormField
                                control={form.control}
                                name="stop_price"
                                render={({ field }) => (
                                    <FormItem>
                                        <FormControl>
                                            <InputComponent symbol={basePair}>
                                                <Label className="text-muted-foreground text-2sm">
                                                    {t('stop')}
                                                </Label>
                                                <InputCurrency
                                                    type="text"
                                                    value={field.value}
                                                    onValueChange={(value) =>
                                                        field.onChange(
                                                            value ?? '',
                                                        )
                                                    }
                                                />
                                            </InputComponent>
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />
                        )}

                        <FormField
                            control={form.control}
                            name="price"
                            render={({ field }) => (
                                <FormItem>
                                    <FormControl>
                                        <InputComponent symbol={basePair}>
                                            <Label className="text-muted-foreground text-2sm">
                                                {t('price')}
                                            </Label>
                                            {!isMarketOrder ? (
                                                <InputCurrency
                                                    type="text"
                                                    value={field.value}
                                                    onValueChange={(value) =>
                                                        field.onChange(
                                                            value ?? '',
                                                        )
                                                    }
                                                />
                                            ) : (
                                                <Input
                                                    className="bg-secondary"
                                                    placeholder={t(
                                                        'market_price',
                                                    )}
                                                    disabled
                                                />
                                            )}
                                        </InputComponent>
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="amount"
                            render={({ field }) => (
                                <FormItem>
                                    <FormControl>
                                        <div className="relative">
                                            <Label className="text-muted-foreground text-2sm">
                                                {t('amount')}
                                            </Label>
                                            <InputCurrency
                                                type="text"
                                                placeholder={t('amount')}
                                                value={field.value}
                                                onValueChange={(value) =>
                                                    field.onChange(value ?? '')
                                                }
                                            />
                                            <span className="absolute top-[34px] right-1.5 text-xs font-medium">
                                                {isMarketOrder ? (
                                                    <DropdownMenu>
                                                        <DropdownMenuTrigger
                                                            asChild
                                                        >
                                                            <div className="flex items-center gap-x-1 cursor-pointer">
                                                                {amountSymbol}
                                                                <ChevronDown
                                                                    size={12}
                                                                />
                                                            </div>
                                                        </DropdownMenuTrigger>
                                                        <DropdownMenuContent
                                                            side="bottom"
                                                            align="end"
                                                        >
                                                            <DropdownMenuItem
                                                                data-active={
                                                                    effectiveTypeAmount ===
                                                                        MarketAmountType.Base
                                                                        ? 'true'
                                                                        : 'false'
                                                                }
                                                                onClick={() =>
                                                                    handleChangeTypeAmount(
                                                                        MarketAmountType.Base,
                                                                    )
                                                                }
                                                            >
                                                                {basePair}
                                                            </DropdownMenuItem>
                                                            <DropdownMenuItem
                                                                data-active={
                                                                    effectiveTypeAmount ===
                                                                        MarketAmountType.Quote
                                                                        ? 'true'
                                                                        : 'false'
                                                                }
                                                                onClick={() =>
                                                                    handleChangeTypeAmount(
                                                                        MarketAmountType.Quote,
                                                                    )
                                                                }
                                                            >
                                                                {currencySymbol}
                                                            </DropdownMenuItem>
                                                        </DropdownMenuContent>
                                                    </DropdownMenu>
                                                ) : (
                                                    amountSymbol
                                                )}
                                            </span>
                                        </div>
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <Slider
                            type={effectiveTypeAmount}
                            side={side}
                            balance={currencyBalance}
                            inputPrice={
                                isMarketOrder ? String(price) : inputPrice
                            }
                            inputAmount={amount}
                            basedValue={
                                side === OrderSide.Buy
                                    ? effectiveTypeAmount ===
                                    MarketAmountType.Base
                                    : true
                            }
                            setInputAmount={(value) =>
                                form.setValue('amount', value, {
                                    shouldDirty: true,
                                    shouldValidate: true,
                                })
                            }
                        />

                        <div className="flex flex-col mt-6 mb-3 space-y-1">
                            {resultOrder.map((item) =>
                                item.isActive ? (
                                    <div
                                        key={item.id}
                                        className="flex justify-between items-center text-2sm"
                                    >
                                        <span className="text-muted-foreground">
                                            {item.title}
                                        </span>
                                        <div className="text-xs flex items-center gap-x-1">
                                            <span>
                                                {item.isFee
                                                    ? item.value
                                                    : decimalPipe(
                                                        item.value,
                                                        item.id === 3 ? 8 : 4,
                                                    )}
                                            </span>
                                            {item.symbol ? (
                                                <span>{item.symbol}</span>
                                            ) : null}
                                            {item.icon ? (
                                                <EqualApproximately size={12} />
                                            ) : null}
                                        </div>
                                    </div>
                                ) : null,
                            )}
                        </div>
                        <div dir={dir}>
                            <AlertError errors={state?.error} />

                        </div>

                        {userId ? (
                            <Button
                                type="submit"
                                variant={
                                    side === OrderSide.Buy
                                        ? 'success'
                                        : 'destructive'
                                }
                                className="w-full"
                                disabled={pending}
                            >
                                {pending ? (
                                    <Spinner className="size-4 animate-spin" />
                                ) : null}

                                {side === OrderSide.Buy
                                    ? `${t('buy')}/${baseSymbol}`
                                    : `${t('sell')}/${baseSymbol}`}
                            </Button>
                        ) : (
                            <Link href="/signin" className="block">
                                <Button
                                    type="button"
                                    variant={
                                        side === OrderSide.Buy
                                            ? 'success'
                                            : 'destructive'
                                    }
                                    className="w-full"
                                >
                                    {t('login')}
                                </Button>
                            </Link>
                        )}
                    </div>
                </form>
            </Form>
        </div>
    );
};

export default memo(FormCreateSpot);
