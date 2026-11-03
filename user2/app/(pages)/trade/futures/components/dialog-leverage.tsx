import {
    startTransition,
    useActionState,
    useContext,
    useEffect,
    useState,
} from 'react';
import { FuturesContext } from '@/store/futures';
import { useQueryClient } from '@tanstack/react-query';
import { SquareMinus, SquarePlus } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { updateLeverage } from '@/services/futures.services';
import { Button } from '@/components/ui/button';
import DialogContent, {
    Dialog,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';
import { LeverageSlider } from './leverage-slider';

export const DialogLeverage = () => {
    const { pair, config, prices } = useContext(FuturesContext);
    const [open, setOpen] = useState(false);
    const [value, setValue] = useState<string>(config.option.futures_leverage);
    const [state, action, pending] = useActionState(updateLeverage, null);

    const maxLeverage = prices?.leverage || 125;
    const t = useTranslations('futures');
    const message_success = t('success_full');

    const queryClient = useQueryClient();

    useEffect(() => {
        let ignore = false;
        if (state?.success) {
            if (!ignore) {
                setOpen(false);
                queryClient.invalidateQueries({
                    queryKey: ['config'],
                });
                toast.success(message_success);
            }
        }
        return () => {
            ignore = true;
        };
    }, [state, queryClient, setOpen, message_success]);

    const closeDialog = () => {
        setOpen(false);
        setValue(config.option.futures_leverage);
    };

    const handleConfirm = () => {
        const data = {
            pairs: pair,
            leverage: +value,
        };
        startTransition(() => action(data));
    };

    const handleMinus = () => {
        const val = +value;
        if (val <= 1) return;
        const min = val - 1;
        setValue(`${min}`);
    };
    const handlePlus = () => {
        const val = +value;
        if (val >= maxLeverage) return;
        const max = val + 1;
        setValue(`${max}`);
    };

    return (
        <>
            <Button
                variant="secondary"
                size="sm"
                className="text-xs !w-24 hover:border hover:shadow-2xs"
                onClick={() => setOpen(true)}
            >
                {value}X
            </Button>

            <Dialog open={open} onOpenChange={closeDialog}>
                <DialogContent className="w-full md:w-[450px]">
                    <DialogHeader>
                        <DialogTitle>Adjust Leverage</DialogTitle>
                    </DialogHeader>
                    <DialogDescription>*Leverage</DialogDescription>
                    <div className="flex flex-col mt-3">
                        <div className="flex flex-col space-y-2">
                            <div className="border rounded-lg shadow-xs p-3 flex items-center justify-between hover:border-primary">
                                <button
                                    type="button"
                                    className="text-muted-foreground hover:text-primary"
                                    onClick={handlePlus}
                                >
                                    <SquarePlus size={20} />
                                </button>
                                <span>{value}X</span>
                                <button
                                    type="button"
                                    className="text-muted-foreground hover:text-primary"
                                    onClick={handleMinus}
                                >
                                    <SquareMinus size={20} />
                                </button>
                            </div>
                            <div dir="ltr" className="mt-3">
                                <LeverageSlider
                                    value={value}
                                    setValue={setValue}
                                    maxLeverage={maxLeverage}
                                />
                            </div>
                        </div>
                        <ul className="text-xs text-muted-foreground list-disc list-inside space-y-3 mt-8">
                            <li>{t('modal_leverage.item_1')}</li>
                            <li>{t('modal_leverage.item_2')}</li>
                            <li className="text-destructive">{t('modal_leverage.item_3')}</li>
                        </ul>
                    </div>
                    <DialogFooter>
                        <AlertError errors={state?.error} />
                        <Button
                            variant="primary"
                            className="w-full"
                            disabled={pending}
                            onClick={handleConfirm}
                        >
                            {pending ? <Spinner className="size-4 animate-spin" /> : null}
                            {t('confirm')}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
};
