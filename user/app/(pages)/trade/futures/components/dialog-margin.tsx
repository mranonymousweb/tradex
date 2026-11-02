import {
    startTransition,
    useActionState,
    useContext,
    useEffect,
    useState,
} from 'react';
import { FuturesContext } from '@/store/futures';
import { useQueryClient } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { updateMarginMode } from '@/services/futures.services';
import { Button } from '@/components/ui/button';
import DialogContent, {
    Dialog,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';

enum MarginMode {
    CROSS = 1,
    ISOLATED = 2,
}

const MARGIN_ITEMS = [
    { title: 'Cross', value: MarginMode.CROSS },
    { title: 'Isolated', value: MarginMode.ISOLATED },
] as const;

const getMarginItem = (mode: MarginMode) =>
    MARGIN_ITEMS.find((item) => item.value === mode) ?? MARGIN_ITEMS[0];

export const DialogMargin = () => {
    const t = useTranslations('futures');
    const { pair, config } = useContext(FuturesContext);

    const queryClient = useQueryClient();
    const savedMargin =
        Number(config?.option?.futures_margin_mode) || MarginMode.CROSS;
    const activeItem = getMarginItem(savedMargin);

    const [open, setOpen] = useState(false);
    const [margin, setMargin] = useState<MarginMode>(savedMargin);
    const [state, action, pending] = useActionState(updateMarginMode, null);
    const messageSuccess = t('success_full');

    useEffect(() => {
        setMargin(savedMargin);
    }, [savedMargin]);

    useEffect(() => {
        if (!state?.success) return;

        setOpen(false);
        toast.success(messageSuccess);
        queryClient.invalidateQueries({ queryKey: ['config'] });
    }, [state, messageSuccess, queryClient]);

    const handleOpenChange = (open: boolean) => {
        setOpen(open);
        if (!open) setMargin(savedMargin);
    };

    const handleConfirm = () => {
        startTransition(() =>
            action({
                pairs: pair,
                margin_mode: margin,
            }),
        );
    };

    return (
        <>
            <Button
                variant="secondary"
                size="sm"
                className="text-xs !w-24 hover:border hover:shadow-2xs"
                onClick={() => setOpen(true)}
            >
                {activeItem.title}
            </Button>

            <Dialog open={open} onOpenChange={handleOpenChange}>
                <DialogContent className="w-full md:w-[450px]">
                    <DialogHeader>
                        <DialogTitle>Margin Mode</DialogTitle>
                    </DialogHeader>
                    <DialogDescription>*Perp</DialogDescription>
                    <div className="flex flex-col mt-3">
                        <div className="flex items-center justify-between space-x-2 mb-4">
                            <Button
                                variant="outline"
                                className={`w-full ${margin === MarginMode.ISOLATED && 'bg-accent'}`}
                                onClick={() => setMargin(MarginMode.ISOLATED)}
                            >
                                Isolated
                            </Button>
                            <Button
                                variant="outline"
                                className={`w-full ${margin === MarginMode.CROSS && 'bg-accent'}`}
                                onClick={() => setMargin(MarginMode.CROSS)}
                            >
                                Cross
                            </Button>
                        </div>
                        <ul className="text-xs text-muted-foreground list-disc list-inside space-x-1">
                            <li>{config?.settings?.mata_data_future_rules_cross}</li>
                            <li>{config?.settings?.mata_data_future_rules_isolated}</li>
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
