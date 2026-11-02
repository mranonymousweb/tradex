import { useActionState, useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { TriangleAlert } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { setConversion } from '@/services/currency-conversion';
import { Button } from '@/components/ui/button';
import DialogContent, {
  Dialog,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';

export const DialogVerify = ({
  id,
  type,
  buttonElement,
}: {
  id?: string;
  type: string;
  buttonElement: React.ReactNode;
}) => {
  const t = useTranslations('wallet.conversion');
  const [open, setOpen] = useState(false);
  const [state, action, pending] = useActionState(setConversion, null);
  const close = () => setOpen(false);
  const router = useRouter();

  useEffect(() => {
    if (state?.success) {
      toast.success(state.message);
      setOpen(false);
      router.refresh();
    }
  }, [state, router]);

  return (
    <>
      <div onClick={() => setOpen(true)}>{buttonElement}</div>
      <Dialog open={open} onOpenChange={close}>
        <DialogContent className="w-full h-full md:h-auto md:w-[450px] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{t('conversion')}</DialogTitle>
          </DialogHeader>
          <AlertError errors={state?.error} />

          <div className="flex flex-col items-center mt-2">
            <span className="bg-orange-50 h-11 flex items-center justify-center p-2 rounded-lg">
              <TriangleAlert size={30} color="oklch(75% 0.183 55.934)" />
            </span>
            <h2 className="text-primary text-base mt-3 mb-2">
              {t('alert_description')}
            </h2>
          </div>

          <DialogFooter>
            <form action={action}>
              <input type="hidden" name="id" defaultValue={id} />
              <input type="hidden" name="type" defaultValue={type} />
              <div className="flex flex-col-reverse md:flex-row items-center">
                <Button
                  type="button"
                  variant="outline"
                  className="mt-2 md:mt-0 w-full mx-2"
                  onClick={close}
                >
                  {t('close')}
                </Button>
                <Button type="submit" disabled={pending} className="w-full">
                  {pending ? <Spinner className="size-4 animate-spin" /> : null}
                  {t('register')}
                </Button>
              </div>
            </form>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
};
