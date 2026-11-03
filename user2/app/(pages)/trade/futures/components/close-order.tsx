import { useActionState, useEffect, useState } from 'react';
import { useQueryClient } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { closeOrder } from '@/services/futures.services';
import { Button } from '@/components/ui/button';
import DialogContent, {
    Dialog,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';

export const CloseOrder = ({
    id,
    buttonElement,
}: {
    id: number;
    buttonElement: React.ReactNode;
}) => {
    const [open, setOpen] = useState(false);
    const t = useTranslations('futures');
    const message_success = t('success_full');
    const [state, action, pending] = useActionState(closeOrder, null);

    const queryClient = useQueryClient();

    useEffect(() => {
        if (state?.success) {
            toast.success(message_success);
            queryClient.invalidateQueries({ queryKey: ['assets_futures'] });
            queryClient.invalidateQueries({ queryKey: ['futures-orders'] });
            setOpen(false);
        }
    }, [state, queryClient, message_success]);

    const closeDialog = () => setOpen(false);

    return (
        <>
            <button
                type="submit"
                className="cursor-pointer"
                onClick={() => setOpen(true)}
            >
                {buttonElement}
            </button>

            <Dialog open={open} onOpenChange={closeDialog}>
                <DialogContent className="w-[450px]">
                    <DialogHeader>
                        <DialogTitle>{t('cancel_order')}</DialogTitle>
                    </DialogHeader>
                    <AlertError errors={state?.error} />
                    <DialogDescription>{t('cancel_order_alert')}</DialogDescription>
                    <DialogFooter>
                        <form action={action} className="flex space-x-2 mt-3">
                            <input type="hidden" name="orderId" defaultValue={id} />
                            <Button type="button" variant="outline" onClick={closeDialog}>
                                {t('cancel')}
                            </Button>
                            <Button disabled={pending} type="submit" variant="primary">
                                {pending ? <Spinner className="animate-spin" /> : null}
                                {t('confirm')}
                            </Button>
                        </form>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
};
