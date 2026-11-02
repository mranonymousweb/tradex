'use client';

import { useState } from 'react';
import { Eye } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { TransactionStatus } from '@/types/wallet';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription as DialogDescriptionText,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

interface Props {
    status: TransactionStatus;
    description?: string | null;
}

export const DialogDescription = ({ status, description }: Props) => {
    const t = useTranslations('wallet.deposit.table.dialog_reject');
    const [open, setOpen] = useState(false);

    const canShow = status === TransactionStatus.Reject;

    if (!canShow) {
        return <span className="text-muted-foreground">...</span>;
    }

    return (
        <>
            <Button
                type="button"
                variant="dim"
                mode="icon"
                className="text-muted-foreground hover:text-primary-active"
                onClick={() => setOpen(true)}
            >
                <Eye size={16} />
            </Button>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent className="w-full md:w-[450px]">
                    <DialogHeader>
                        <DialogTitle>{t('title')}</DialogTitle>
                    </DialogHeader>
                    <DialogDescriptionText className="text-xs text-secondary-foreground">
                        {t('description')}
                    </DialogDescriptionText>
                    <div className="flex justify-center my-5 text-primary">
                        <span className="text-destructive p-3 rounded-lg text-sm text-justify bg-destructive/5">
                            {description || t('no_description')}
                        </span>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => setOpen(false)}
                        >
                            {t('close')}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
};
