import { useActionState, useEffect, useState } from 'react';
import Image from 'next/image';
import { TriangleAlert } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { StockRow } from '@/types/stock';
import { AlertError } from '@/hooks/alert-error';
import { rejectStock } from '@/services/stock.services';
import { Button } from '@/components/ui/button';
import DialogContent, {
  Dialog,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';

export const RejectStock = ({ currency }: { currency: StockRow }) => {
  const t = useTranslations('stock');
  const [open, setOpen] = useState(false);
  const [state, action, pending] = useActionState(rejectStock, null);
  const close = () => setOpen(false);

  useEffect(() => {
    if (state?.success) {
      toast.success(state.message);
      setOpen(false);
    } else if (state?.error) {
      toast.error(state.error);
    }
  }, [state]);

  return (
    <>
      <Button variant="destructive" onClick={() => setOpen(true)}>
        {t('cancel')}
      </Button>

      <Dialog open={open} onOpenChange={close}>
        <DialogContent
          close={false}
          className="w-full h-full md:h-auto md:w-[450px] overflow-y-auto"
        >
          <DialogHeader>
            <DialogTitle className="flex items-center gap-1">
              <div className="flex items-center gap-1">
                <Image
                  src={currency.currency.icon}
                  width={25}
                  height={25}
                  alt="icon"
                />
                <span className="inline-block pt-1">
                  {currency.currency.symbol}
                </span>
              </div>
              <span className="text-sm">({t('interest_cancel')})</span>
            </DialogTitle>
          </DialogHeader>

          <div className="flex flex-col items-center mt-2">
            <span className="bg-orange-50 h-11 flex items-center justify-center p-2 rounded-lg">
              <TriangleAlert size={30} color="oklch(75% 0.183 55.934)" />
            </span>
            <h2 className="text-orange-500 text-base mt-3 mb-2">
              {t('alert_cancel')}
            </h2>
          </div>
          <DialogFooter>
            <form action={action} className="flex items-center">
              <input type="hidden" name="id" defaultValue={currency.id} />
              <Button
                type="button"
                variant="outline"
                className="mt-2 md:mt-0 mx-2"
                onClick={close}
              >
                {t('close')}
              </Button>
              <Button type="submit" disabled={pending}>
                {pending ? <Spinner className="size-4 animate-spin" /> : null}
                {t('confirm_accept')}
              </Button>
            </form>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
};
