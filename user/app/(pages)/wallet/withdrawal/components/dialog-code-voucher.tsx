/* eslint-disable @typescript-eslint/no-explicit-any */

import {
  Fragment,
  startTransition,
  useActionState,
  useContext,
  useEffect,
  useState,
} from 'react';
import { WalletContext } from '@/store/wallet';
import { DialogDescription } from '@radix-ui/react-dialog';
import { Copy, SquareMenu } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { verifyVoucher } from '@/services/wallet.services';
import { Alert, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import DialogContent, {
  Dialog,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Input, InputWrapper } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinners';
import { VerifyCode } from '@/app/(pages)/settings-account/components';

interface Props {
  txtId: string | null;
  tag: string | null;
  status: number;
}

export const DialogCodeVoucher: React.FC<Props> = ({ txtId, tag, status }) => {
  const t = useTranslations('wallet.withdrawal');
  const { googleAuth } = useContext(WalletContext);
  const [code, setCode] = useState('');
  const [open, setOpen] = useState(false);
  const [showCode, setShowCode] = useState(false);
  const [invalid, setInvalid] = useState(false);
  const [state, action, pending] = useActionState(verifyVoucher, null);

  useEffect(() => {
    if (!state) return;

    if (state?.success) {
      setShowCode(true);
    } else {
      setInvalid(true);
    }
  }, [state]);

  const closeDialog = () => {
    setOpen(false);
  };

  const handleOpenDialog = () => {
    if (Number(status) !== 2) {
      toast.error(t('alert_voucher_toast'));
      return;
    }
    setOpen(true);
  };

  const handleSubmit = () => {
    const formData = { code, section: 'withdraw' };
    startTransition(() => action(formData));
  };

  const handleCopyText = (text: string) => {
    navigator.clipboard.writeText(text);
    toast.success(t('coped'));
  };

  return (
    <Fragment>
      <Button variant="outline" onClick={handleOpenDialog}>
        <SquareMenu />
        {t('show_code')}
      </Button>
      <Dialog open={open} onOpenChange={closeDialog}>
        {showCode ? (
          <DialogContent className="w-[450px]">
            <DialogHeader>
              <DialogTitle>{t('code_voucher')}</DialogTitle>
            </DialogHeader>
            <DialogDescription className="text-xs text-secondary-foreground mb-2">
              {t('description_dialog_vocher')}
            </DialogDescription>

            <div className="mt-4 grow flex flex-col gap-y-2">
              {txtId && (
                <div>
                  <Label>{t('txid_voucher')}</Label>
                  <InputWrapper>
                    <Input defaultValue={txtId} />
                    <Button
                      type="button"
                      variant="dim"
                      mode="icon"
                      className="-me-2"
                      onClick={() => handleCopyText(txtId)}
                    >
                      <Copy size={16} />
                    </Button>
                  </InputWrapper>
                </div>
              )}
              {tag && (
                <div>
                  <Label>{t('tag')}</Label>
                  <InputWrapper>
                    <Input defaultValue={tag} />
                    <Button
                      type="button"
                      variant="dim"
                      mode="icon"
                      className="-me-2"
                      onClick={() => handleCopyText(tag)}
                    >
                      <Copy size={16} />
                    </Button>
                  </InputWrapper>
                </div>
              )}
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={closeDialog}>
                {t('close')}
              </Button>
            </DialogFooter>
          </DialogContent>
        ) : (
          <DialogContent className="w-[450px]">
            <DialogHeader>
              <DialogTitle>{t('confirm_transaction')}</DialogTitle>
            </DialogHeader>
            <DialogDescription className="text-xs text-secondary-foreground mb-2">
              {googleAuth
                ? '*Google Authenticator'
                : t('description_header_dialog')}
            </DialogDescription>
            <AlertError errors={state?.error} />
            {invalid && (
              <Alert variant="destructive" appearance="light">
                <AlertTitle>{t('invalid_code')}</AlertTitle>
              </Alert>
            )}
            <div className="mt-4">
              <div className="flex items-center justify-between">
                {googleAuth ? (
                  <Label>{t('enter_code_google')}</Label>
                ) : (
                  <Label>{t('code_confirm')}</Label>
                )}
                {!googleAuth && <VerifyCode section="withdraw" />}
              </div>
              <Input
                value={code}
                onChange={(e) => setCode(e.target.value)}
                placeholder={t('enter_code_confirm')}
              />
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={closeDialog}>
                {t('close')}
              </Button>
              <Button type="button" disabled={pending} onClick={handleSubmit}>
                {pending ? <Spinner className="size-4 animate-spin" /> : null}
                {t('register')}
              </Button>
            </DialogFooter>
          </DialogContent>
        )}
      </Dialog>
    </Fragment>
  );
};
