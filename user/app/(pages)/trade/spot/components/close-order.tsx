import { useActionState, useEffect, useState } from 'react';
import { useQueryClient } from '@tanstack/react-query';
import { Trash2 } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { closeOrder } from '@/services/spot.services';
import { Button } from '@/components/ui/button';
import DialogContent, {
  Dialog,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';

export const CloseOrder = ({ id }: { id: number }) => {
  const t = useTranslations('spot');
  const [open, setOpen] = useState(false);
  const [state, action, pending] = useActionState(closeOrder, null);
  const queryClient = useQueryClient();

  useEffect(() => {
    if (state?.success) {
      toast.success(t('message_success'));
      queryClient.invalidateQueries({ queryKey: ['spot-balance'] });
      queryClient.invalidateQueries({ queryKey: ['order-market'] });
      setOpen(false);
    }
  }, [state, queryClient]);
  const closeDialog = () => setOpen(false);

  return (
    <>
      <button
        type="submit"
        className="cursor-pointer"
        onClick={() => setOpen(true)}
      >
        <Trash2 size={18} color="oklch(57.7% 0.245 27.325)" />
      </button>

      <Dialog open={open} onOpenChange={closeDialog}>
        <DialogContent className="w-[450px]">
          <DialogHeader>
            <DialogTitle>{t('cancel_order')}</DialogTitle>
          </DialogHeader>
          <AlertError errors={state?.error} />
          <DialogDescription>{t('approval_cancel_order')}</DialogDescription>
          <DialogFooter>
            <form action={action} className="flex space-x-2 mt-3">
              <input type="hidden" name="id" defaultValue={id} />
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
