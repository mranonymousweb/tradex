/* eslint-disable react-hooks/exhaustive-deps */
import { useActionState, useContext, useEffect, useState } from 'react';
import { SwapContext } from '@/store/swap';
import { useQueryClient } from '@tanstack/react-query';
import { TriangleAlert } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { createSwap } from '@/services/swap.services';
import { Alert } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import DialogContent, {
  Dialog,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';

export const ButtonAction = () => {
  const {
    inputReceive,
    inputPayment,
    selectedPayment,
    selectedReceive,
    setSelectedPayment,
    setInputPayment,
    setInputReceive,
  } = useContext(SwapContext);
  const t = useTranslations('swap.dialog');
  const [open, setOpen] = useState(false);
  const [validation, setValidation] = useState<string | null>(null);
  const [state, action, pending] = useActionState(createSwap, null);
  const queryClient = useQueryClient();
  const closeDialog = () => setOpen(false);

  useEffect(() => {
    if (!state?.success) return;

    queryClient.invalidateQueries({ queryKey: ['swap-orders'] });
    queryClient.invalidateQueries({ queryKey: ['swap-currency'] });
    toast.success(t('success'), { position: 'top-right' });

    // update payment
    setSelectedPayment({
      ...selectedPayment,
      currency: {
        ...selectedPayment.currency,
        balance: state.balance.from_currency_balance,
      },
    });

    //reset Form
    setInputPayment('');
    setInputReceive('');
    setValidation('');
    setOpen(false);
  }, [state]);

  const handleOPen = () => {
    if (!inputPayment) {
      toast.error(t('validate_payment'));
      return;
    }
    if (!inputReceive) {
      toast.error(t('validate_received'));
      return;
    }
    if (+inputPayment > +selectedPayment.currency.balance) {
      toast.error(t('validate_balance'));
      return;
    }
    if (selectedPayment.currency.symbol === selectedReceive.currency.symbol) {
      toast.error(t('error_transfer'));
      return;
    }
    setOpen(true);
  };

  return (
    <>
      {validation && (
        <Alert
          variant="destructive"
          appearance="light"
          className="text-xs flex items-center gap-x-2"
        >
          <i className="ki-filled ki-information-4 text-destructive text-lg"></i>
          {validation}
        </Alert>
      )}
      <Button className="w-full" onClick={handleOPen}>
        {t('transform')}
      </Button>

      <Dialog open={open} onOpenChange={closeDialog}>
        <DialogContent className="w-[450px]">
          <DialogHeader>
            <DialogTitle>{t('confirm_order')}</DialogTitle>
          </DialogHeader>
          <AlertError errors={state?.error} />
          <div className="flex flex-col items-center mt-2">
            <span className="bg-orange-50 h-11 flex items-center justify-center p-2 rounded-lg">
              <TriangleAlert size={30} color="oklch(75% 0.183 55.934)" />
            </span>
            <h2 className="text-mono text-base mt-3 mb-2">
              {t('alert_description')}
            </h2>
          </div>
          <DialogFooter>
            <form action={action} className="flex space-x-2 mt-3">
              <input
                type="hidden"
                name="from_currency_id"
                defaultValue={selectedPayment.currency.id}
              />
              <input
                type="hidden"
                name="to_currency_id"
                defaultValue={selectedReceive.currency.id}
              />
              <input type="hidden" name="amount" defaultValue={inputPayment} />

              <Button type="button" variant="outline" onClick={closeDialog}>
                {t('cancel')}
              </Button>
              <Button type="submit" variant="primary">
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
