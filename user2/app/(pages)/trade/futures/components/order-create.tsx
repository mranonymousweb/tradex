import {
    startTransition,
    useActionState,
    useCallback,
    useContext,
    useEffect,
    useState,
} from 'react';
import Link from 'next/link';
import { FuturesContext } from '@/store/futures';
import { useQueryClient } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { UseFormReturn } from 'react-hook-form';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { addOrder } from '@/services/futures.services';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';
import { cn } from '@/lib/utils';
import { FormType, OrderSide } from './constants';
import { FormSchemaType } from './schema';

interface Props {
    form: UseFormReturn<FormSchemaType>;
    invalid: boolean;
    marketPrice: string;
    onSuccess: () => void;
}

const MARKET_ORDER_TYPES = new Set<number>([
    FormType.Market,
    FormType.StopMarket,
]);

const ORDER_BUTTONS = [
    {
        side: OrderSide.Buy,
        label: 'Buy/Long',
        className: 'bg-green text-background hover:opacity-80 hover:bg-green',
    },
    {
        side: OrderSide.Sell,
        label: 'Sell/Short',
        className:
            'bg-destructive/70 text-background hover:opacity-80 hover:bg-destructive/70 dark:text-white',
    },
] as const;

const BUTTON_CLASS = 'w-full hover:opacity-80';
const BUTTON_ROW_CLASS = 'flex items-center space-x-2 mt-5';

export const OrderCreate = ({
    form,
    invalid,
    marketPrice,
    onSuccess,
}: Props) => {
    const { userId, balance } = useContext(FuturesContext);
    const queryClient = useQueryClient();
    const t = useTranslations('futures');
    const successMessage = t('success_full');
    const [pendingSide, setPendingSide] = useState<OrderSide | null>(null);
    const [state, action, pending] = useActionState(addOrder, null);

    useEffect(() => {
        if (!state) return;

        if (state.success) {
            toast.success(successMessage);
            onSuccess();
            queryClient.invalidateQueries({ queryKey: ['assets_futures'] });
            queryClient.invalidateQueries({ queryKey: ['futures-orders'] });
        } else if (state.error) {
            state.error.forEach(([, value]: [string, unknown]) => {
                toast.error(String(value));
            });
        }

        setPendingSide(null);
    }, [state, queryClient, onSuccess, successMessage]);

    const handleSubmit = useCallback(
        async (side: OrderSide) => {
            form.setValue('side', side);
            form.setValue('enter_balance', String(balance));

            const type = form.getValues('type');
            if (MARKET_ORDER_TYPES.has(type)) {
                form.setValue('enter_price', marketPrice);
            }

            const valid = await form.trigger();
            if (!valid || invalid) return;

            setPendingSide(side);
            startTransition(() => action(form.getValues()));
        },
        [action, balance, form, invalid, marketPrice],
    );

    if (!userId) {
        return (
            <div className={BUTTON_ROW_CLASS}>
                {ORDER_BUTTONS.map(({ side, className }) => (
                    <Link key={side} href="/signin" className="block w-full">
                        <Button className={cn(className, BUTTON_CLASS)}>
                            Login
                        </Button>
                    </Link>
                ))}
            </div>
        );
    }

    return (
        <>
            <div className={BUTTON_ROW_CLASS}>
                {ORDER_BUTTONS.map(({ side, label, className }) => (
                    <Button
                        type="button"
                        key={side}
                        onClick={() => handleSubmit(side)}
                        className={cn(className, BUTTON_CLASS)}
                        disabled={pending || invalid}
                    >
                        {pending && pendingSide === side ? (
                            <Spinner className="size-4 animate-spin" />
                        ) : null}
                        {label}
                    </Button>
                ))}
            </div>
            <AlertError errors={state?.error} />
        </>
    );
};
