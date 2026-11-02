'use client';

import {
    useCallback,
    useContext,
    useEffect,
    useMemo,
    useRef,
    useState,
} from 'react';
import Link from 'next/link';
import { decimalPipe } from '@/helper/decimalPipe';
import { CalcLiqPrice } from '@/helper/futures/liqPrice';
import { FuturesContext } from '@/store/futures';
import { zodResolver } from '@hookform/resolvers/zod';
import { CircleAlert, Wallet } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { useForm } from 'react-hook-form';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormMessage,
} from '@/components/ui/form';
import InputCurrency from '@/components/ui/input-currency';
import { Label } from '@/components/ui/label';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { DialogLeverage } from './dialog-leverage';
import { DialogMargin } from './dialog-margin';
import { FormType } from './constants';
import { OrderCreate } from './order-create';
import { FormSchema, FormSchemaType } from './schema';
import { Slider } from './Slider';
import { Tabs } from './tabs';
import { DotsLoader } from '@/components/ui/dots-loader';

export const Forms = () => {
    const { balance, config, pair, prices, isLoading, totalOrders } =
        useContext(FuturesContext);
    const t = useTranslations('futures');

    const symbol = pair.split('_')[0];
    const baseCurrency = pair.split('_')[1];
    const livePrice =
        prices?.pairs === pair ? String(prices?.price ?? '') : '';

    const [marketPrice, setMarketPrice] = useState('');
    const snappedPairRef = useRef<string | null>(null);

    useEffect(() => {
        snappedPairRef.current = null;
        setMarketPrice('');
    }, [pair]);

    useEffect(() => {
        if (!livePrice) return;
        if (snappedPairRef.current === pair) return;

        setMarketPrice(livePrice);
        snappedPairRef.current = pair;
    }, [pair, livePrice]);

    const form = useForm<FormSchemaType>({
        resolver: zodResolver(FormSchema(t)),
        defaultValues: {
            main_currency: symbol,
            base_currency: baseCurrency,
            side: 1,
            type: FormType.Limit,
            enter_price: '',
            enter_balance: '',
            quantity: '',
            has_tpsl: false,
            take_profit: '',
            stop_loss: '',
            stop_limit: '',
            stop_market: '',
        },
    });

    const typeOrder = Number(form.watch('type'));
    const quantity = form.watch('quantity');
    const enterPrice = form.watch('enter_price');
    const stopMarket = form.watch('stop_market');
    const hasTpsl = form.watch('has_tpsl');

    const isStopLimit = typeOrder === FormType.StopLimit;
    const isStopMarket = typeOrder === FormType.StopMarket;
    const isMarketOrder = typeOrder === FormType.Market;

    const effectivePrice = useMemo(() => {
        if (isMarketOrder) return marketPrice;
        if (isStopMarket) return stopMarket || marketPrice;
        return enterPrice ?? '';
    }, [isMarketOrder, isStopMarket, marketPrice, stopMarket, enterPrice]);

    const { cost, costBuy, costSell, max, invalid } = useMemo(() => {
        if (!effectivePrice || !quantity) {
            return { cost: 0, max: 0, invalid: false };
        }

        const priceNum = Number(effectivePrice);
        const quantityNum = Number(quantity);
        const orderAmount = priceNum * quantityNum;
        const longFee = config?.settings?.future_long_position_fee;
        const shortFee = config?.settings?.future_short_position_fee;

        //buy fee is 0.1%
        const feeBuy = (longFee / 100) * orderAmount;
        const costBuy = orderAmount - feeBuy;

        //sell fee is 10%
        const feeSell = (shortFee / 100) * orderAmount;
        const costSell = orderAmount - feeSell;

        return {
            cost: quantityNum * priceNum,
            costBuy: longFee ? costBuy : orderAmount,
            costSell: shortFee ? costSell : orderAmount,
            max: priceNum > 0 ? balance / priceNum : 0,
            invalid: orderAmount > balance,
        };
    }, [effectivePrice, quantity, balance]);

    const { long, short } = useMemo(
        () => CalcLiqPrice(effectivePrice, quantity, balance, config),
        [effectivePrice, quantity, balance, config],
    );
    useEffect(() => {
        form.setValue('main_currency', symbol);
        form.setValue('base_currency', baseCurrency);
        form.setValue('enter_balance', String(balance));
    }, [pair, balance, symbol, baseCurrency, form]);

    useEffect(() => {
        if (!marketPrice) return;

        form.setValue('enter_price', marketPrice);
    }, [pair, marketPrice, form]);

    useEffect(() => {
        if (!totalOrders) return;

        form.setValue('enter_price', String(totalOrders.price));
        form.setValue('quantity', String(totalOrders.amount));
    }, [totalOrders, form]);

    useEffect(() => {
        form.resetField('quantity');
        form.resetField('stop_limit');
        form.resetField('stop_market');
        form.setValue('has_tpsl', false);
        form.resetField('take_profit');
        form.resetField('stop_loss');

        if (![FormType.Limit, FormType.StopLimit].includes(typeOrder)) {
            form.clearErrors('enter_price');
        }
    }, [typeOrder, form]);

    const resetForm = useCallback(() => {
        form.resetField('quantity');
        form.setValue('has_tpsl', false);
        form.resetField('take_profit');
        form.resetField('stop_loss');
        form.resetField('stop_limit');
        form.resetField('stop_market');
        form.setValue('enter_price', marketPrice);
    }, [form, marketPrice]);

    return (
        isLoading ? (
            <div className="w-full h-full bg-background">
                <DotsLoader />
            </div>
        ) : (
            <div
                dir="ltr"
                className="form-actions bg-background w-full p-3 rounded-lg shadow-xs h-full"
            >
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-x-2">
                        <DialogMargin />
                        <DialogLeverage />
                    </div>
                </div>

                <Form {...form}>
                    <form>
                        <div className="flex items-center justify-between mt-6">
                            <Tabs form={form} />
                            <TooltipProvider>
                                <Tooltip delayDuration={50}>
                                    <TooltipTrigger className="cursor-pointer">
                                        <CircleAlert size={17} fill="#9a9c9f" color="white" />
                                    </TooltipTrigger>
                                    <TooltipContent className="text-xs w-64">
                                        Stop Limit Orders become limit orders when a specified stop
                                        price is reached. Specify a trigger price to activate the
                                        order
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                        </div>

                        <div className="flex items-center justify-between my-3">
                            <span className="text-xs text-muted-foreground">
                                Avble{' '}
                                <b className="text-foreground">{decimalPipe(balance, 2)}</b> USDT
                            </span>
                            <Link href="/wallet/futures">
                                <Wallet size={15} color="#9f9fa9" />
                            </Link>
                        </div>

                        <div className="space-y-2">
                            {isStopLimit && (
                                <FormField
                                    control={form.control}
                                    name="stop_limit"
                                    render={({ field }) => (
                                        <FormItem>
                                            <FormControl>
                                                <div className="relative">
                                                    <Label className="text-muted-foreground text-2sm">
                                                        Stop Price
                                                    </Label>
                                                    <InputCurrency
                                                        type="text"
                                                        value={field.value}
                                                        onValueChange={(value) => field.onChange(value ?? '')}
                                                    />
                                                    <span className="absolute top-[34px] right-1.5 text-xs font-medium">
                                                        {baseCurrency}
                                                    </span>
                                                </div>
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    )}
                                />
                            )}

                            {isStopMarket && (
                                <FormField
                                    control={form.control}
                                    name="stop_market"
                                    render={({ field }) => (
                                        <FormItem>
                                            <FormControl>
                                                <div className="relative">
                                                    <Label className="text-muted-foreground text-2sm">
                                                        Stop Price
                                                    </Label>
                                                    <InputCurrency
                                                        type="text"
                                                        value={field.value}
                                                        onValueChange={(value) => field.onChange(value ?? '')}
                                                    />
                                                    <span className="absolute top-[34px] right-1.5 text-xs font-medium">
                                                        {baseCurrency}
                                                    </span>
                                                </div>
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    )}
                                />
                            )}

                            {(typeOrder === FormType.Limit ||
                                typeOrder === FormType.StopLimit) && (
                                    <FormField
                                        control={form.control}
                                        name="enter_price"
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormControl>
                                                    <div className="relative">
                                                        <Label className="text-muted-foreground text-2sm">
                                                            Price
                                                        </Label>
                                                        <InputCurrency
                                                            type="text"
                                                            value={field.value}
                                                            onValueChange={(value) =>
                                                                field.onChange(value ?? '')
                                                            }
                                                        />
                                                        <span className="absolute top-[34px] right-1.5 text-xs font-medium">
                                                            {baseCurrency}
                                                        </span>
                                                    </div>
                                                </FormControl>
                                                <FormMessage />
                                            </FormItem>
                                        )}
                                    />
                                )}

                            <FormField
                                control={form.control}
                                name="quantity"
                                render={({ field }) => (
                                    <FormItem>
                                        <FormControl>
                                            <div className="relative">
                                                <Label className="text-muted-foreground text-2sm">
                                                    Size
                                                </Label>
                                                <InputCurrency
                                                    type="text"
                                                    value={field.value}
                                                    onValueChange={(value) => field.onChange(value ?? '')}
                                                    placeholder="0"
                                                    readOnly={balance <= 0}
                                                    style={{ borderColor: invalid ? 'red' : undefined }}
                                                />
                                                <span className="absolute top-[34px] right-1.5 text-xs font-medium">
                                                    {symbol}
                                                </span>
                                            </div>
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />

                            <Slider
                                type={typeOrder}
                                marketPrice={marketPrice}
                                enterPrice={enterPrice}
                                balance={balance}
                                quantity={quantity}
                                setInputAmount={(value) =>
                                    form.setValue('quantity', value, {
                                        shouldDirty: true,
                                        shouldValidate: true,
                                    })
                                }
                            />

                            <div className="flex items-center justify-between mt-5 text-xs text-muted-foreground">
                                <span>Buy {decimalPipe(cost, 2)} USDT</span>
                                <span>Sell {decimalPipe(cost, 2)} USDT</span>
                            </div>

                            <hr className="my-5" />

                            <FormField
                                control={form.control}
                                name="has_tpsl"
                                render={({ field }) => (
                                    <FormItem>
                                        <FormControl>
                                            <div className="flex items-center space-x-2">
                                                <Checkbox
                                                    checked={field.value}
                                                    onCheckedChange={(checked) =>
                                                        field.onChange(checked === true)
                                                    }
                                                />
                                                <Label>TP/SL</Label>
                                            </div>
                                        </FormControl>
                                    </FormItem>
                                )}
                            />

                            {hasTpsl && (
                                <div className="flex flex-col">
                                    <FormField
                                        control={form.control}
                                        name="take_profit"
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormControl>
                                                    <div className="relative my-2">
                                                        <Label className="text-muted-foreground text-2sm">
                                                            Take Profit
                                                        </Label>
                                                        <InputCurrency
                                                            type="text"
                                                            value={field.value}
                                                            onValueChange={(value) =>
                                                                field.onChange(value ?? '')
                                                            }
                                                        />
                                                        <span className="absolute top-[34px] right-1.5 text-xs font-medium">
                                                            USDT
                                                        </span>
                                                    </div>
                                                </FormControl>
                                            </FormItem>
                                        )}
                                    />
                                    <FormField
                                        control={form.control}
                                        name="stop_loss"
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormControl>
                                                    <div className="relative">
                                                        <Label className="text-muted-foreground text-2sm">
                                                            Stop loss
                                                        </Label>
                                                        <InputCurrency
                                                            type="text"
                                                            value={field.value}
                                                            onValueChange={(value) =>
                                                                field.onChange(value ?? '')
                                                            }
                                                        />
                                                        <span className="absolute top-[34px] right-1.5 text-xs font-medium">
                                                            USDT
                                                        </span>
                                                    </div>
                                                </FormControl>
                                            </FormItem>
                                        )}
                                    />
                                </div>
                            )}

                            <OrderCreate
                                form={form}
                                invalid={invalid}
                                marketPrice={marketPrice}
                                onSuccess={resetForm}
                            />

                            <div className="flex items-start justify-between mt-3">
                                <div className="flex flex-col text-xs gap-y-1">
                                    <div className="flex md:flex-col 2xl:flex-row 2xl:items-center space-x-2">
                                        <span className="text-muted-foreground">Liq P..</span>
                                        <span>
                                            {decimalPipe(long, 4)} <small>USDT</small>
                                        </span>
                                    </div>
                                    <div className="flex md:flex-col 2xl:flex-row 2xl:items-center space-x-2">
                                        <span className="text-muted-foreground">Cost</span>
                                        <span>{decimalPipe(String(costBuy), 2)} USDT</span>
                                    </div>
                                    <div className="flex md:flex-col 2xl:flex-row 2xl:items-center space-x-2">
                                        <span className="text-muted-foreground">Max</span>
                                        <span>
                                            {decimalPipe(max, 2)} {symbol}
                                        </span>
                                    </div>
                                </div>
                                <div className="flex flex-col text-xs items-end gap-y-1">
                                    <div className="flex md:flex-col 2xl:flex-row 2xl:items-center space-x-2">
                                        <span className="text-muted-foreground">Liq P..</span>
                                        <span>
                                            {decimalPipe(short, 3)} <small>USDT</small>
                                        </span>
                                    </div>
                                    <div className="flex md:flex-col md:items-end 2xl:flex-row 2xl:items-center space-x-2">
                                        <span className="text-muted-foreground">Cost</span>
                                        <span>{decimalPipe(String(costSell), 2)} USDT</span>
                                    </div>
                                    <div className="flex md:flex-col md:items-end 2xl:flex-row 2xl:items-center space-x-2">
                                        <span className="text-muted-foreground">Max</span>
                                        <span>
                                            {decimalPipe(max, 2)} {symbol}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </Form>
            </div>
        )
    );
};
