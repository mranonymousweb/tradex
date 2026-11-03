import { startTransition, useActionState, useEffect, useState } from 'react';
import { useQueryClient } from '@tanstack/react-query';
import { SquarePen } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { Position } from '@/types/futures';
import { AlertError } from '@/hooks/alert-error';
import { updateTpsl } from '@/services/futures.services';
import { Button } from '@/components/ui/button';
import DialogContent, {
    Dialog,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import InputCurrency from '@/components/ui/input-currency';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinners';

export const UpdateTpsl = ({ items }: { items: Position }) => {
    const [open, setOpen] = useState(false);
    const queryClient = useQueryClient();
    const [takeProfit, setTakeProfit] = useState(items.take_profit);
    const [stopLoss, setStopLoss] = useState(items.stop_loss);
    const [state, action, pending] = useActionState(updateTpsl, null);
    const t = useTranslations('futures');
    const message_success = t('success_full');

    useEffect(() => {
        if (state?.success) {
            toast.success(message_success);
            queryClient.invalidateQueries({ queryKey: ['futures-orders'] });
            setOpen(false);
        }
    }, [state, queryClient, message_success]);

    const handleAction = () => {
        const data = {
            id: +items.id,
            has_tpsl: true,
            take_profit: Number(takeProfit),
            stop_loss: stopLoss,
        };
        startTransition(() => action(data));
    };

    const closeDialog = () => setOpen(false);

    return (
        <>
            <button
                type="submit"
                className="cursor-pointer"
                onClick={() => setOpen(true)}
            >
                <SquarePen size={13} className="mb-1" />
            </button>

            <Dialog open={open} onOpenChange={closeDialog}>
                <DialogContent className="w-[450px]">
                    <DialogHeader>
                        <DialogTitle>{t('edit_tpsl')}</DialogTitle>
                    </DialogHeader>
                    <AlertError errors={state?.error} />
                    <DialogDescription>* Take Profit && Stop Loss</DialogDescription>
                    <form className="flex flex-col mt-3">
                        <div className="relative my-2">
                            <Label className="text-muted-foreground text-2sm">
                                Take Profit
                            </Label>
                            <InputCurrency
                                type="text"
                                value={takeProfit ?? '0'}
                                onValueChange={(_val) => setTakeProfit(_val ?? '0')}
                                placeholder=""
                            />
                            <span className="absolute top-[34px] left-1.5 text-xs font-medium">
                                USDT
                            </span>
                        </div>
                        <div className="relative">
                            <Label className="text-muted-foreground text-2sm">
                                Stop Loss
                            </Label>
                            <InputCurrency
                                type="text"
                                value={stopLoss ?? '0'}
                                onValueChange={(_val) => setStopLoss(_val ?? '0')}
                                placeholder=""
                            />
                            <span className="absolute top-[34px] left-1.5 text-xs font-medium">
                                USDT
                            </span>
                        </div>
                        <DialogFooter>
                            <div className="flex gap-x-2">
                                <Button type="button" variant="outline" onClick={closeDialog}>
                                    {t('cancel')}
                                </Button>
                                <Button type="button" variant="primary" onClick={handleAction}>
                                    {pending ? <Spinner className="animate-spin" /> : null}
                                    {t('confirm')}
                                </Button>
                            </div>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </>
    );
};
