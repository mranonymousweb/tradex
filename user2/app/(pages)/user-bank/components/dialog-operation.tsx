import { useActionState, useEffect, useState } from 'react';
import { DialogDescription } from '@radix-ui/react-dialog';
import { useQueryClient } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { Status, StatusBank } from '@/types/bank';
import { toggleStatus } from '@/services/banks.services';
import { Button } from '@/components/ui/button';
import DialogContent, {
  Dialog,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';

interface Props {
  id: number;
  status: Status;
  description: string;
}

export const Operation = ({ id, status, description }: Props) => {
  const t = useTranslations('bank.dialog');
  const [open, setOpen] = useState(false);
  const [state, action, pending] = useActionState(toggleStatus, null);
  const queryClient = useQueryClient();
  const closeDialog = () => setOpen(false);

  const btnVariant =
    status === StatusBank.INACTIVE
      ? 'success'
      : status === StatusBank.WAITING
        ? 'dim'
        : 'destructive';

  useEffect(() => {
    if (state?.success) {
      toast.success(state.message);
      queryClient.invalidateQueries({
        queryKey: ['card-banks'],
      });
      setOpen(false);
    } else if (state?.message) {
      toast.error(state.message);
    }
  }, [state, queryClient]);

  return (
    <>
      <Button
        type="button"
        variant={btnVariant}
        disabled={status === StatusBank.WAITING}
        onClick={() => setOpen(true)}
      >
        {status === StatusBank.ACTIVE
          ? t('not_active')
          : status === StatusBank.INACTIVE
            ? t('active')
            : status === StatusBank.REJECTED
              ? t('reason_reject')
              : '---'}
      </Button>

      <Dialog open={open} onOpenChange={closeDialog}>
        <DialogContent className="w-full md:w-[450px]">
          <DialogHeader>
            {status === StatusBank.REJECTED ? (
              <DialogTitle>{t('reason_reject')}</DialogTitle>
            ) : (
              <DialogTitle>{t('change_status')}</DialogTitle>
            )}
          </DialogHeader>
          <DialogDescription className="text-xs text-secondary-foreground">
            {status === StatusBank.REJECTED ? (
              <span>{t('reason_reject_bank')}</span>
            ) : (
              <span>{t('title_dialog')}</span>
            )}
          </DialogDescription>
          <div className="flex justify-center my-5 text-primary">
            {status === StatusBank.INACTIVE ? (
              <span>{t('alert_active_card')}</span>
            ) : status === StatusBank.ACTIVE ? (
              <span>{t('alert_notActive_card')}</span>
            ) : status === StatusBank.REJECTED ? (
              <span className="text-destructive">{description}</span>
            ) : (
              <span>{t('awaiting')}</span>
            )}
          </div>

          {status !== StatusBank.REJECTED && (
            <DialogFooter>
              <Button type="button" variant="outline" onClick={closeDialog}>
                {t('close')}
              </Button>
              <form action={action}>
                <input type="hidden" name="id" defaultValue={id} />
                <input
                  type="hidden"
                  name="status"
                  defaultValue={
                    status === StatusBank.ACTIVE ? 'inactive' : 'active'
                  }
                />
                <Button type="submit" className="w-full mb-2 md:mb-0">
                  {pending ? <Spinner className="size-4 animate-spin" /> : null}
                  {t('confirm_register')}
                </Button>
              </form>
            </DialogFooter>
          )}
        </DialogContent>
      </Dialog>
    </>
  );
};
