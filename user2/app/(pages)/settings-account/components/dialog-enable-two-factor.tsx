import { startTransition, useActionState, useEffect, useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { Copy, TriangleAlert } from 'lucide-react';
import { useTranslations } from 'next-intl';
import QRCode from 'react-qr-code';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import {
  getKeyGoogleAuth,
  toggleGoogleAuth,
} from '@/services/setting-account.services';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import DialogContent, {
  Dialog,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Input, InputWrapper } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinners';
import { Switch } from '@/components/ui/switch';

export const SwitchToTP = ({ totpUser }: { totpUser: boolean }) => {
  const t = useTranslations('profile');
  const [checked, setChecked] = useState(totpUser);
  const [open, setOpen] = useState(false);
  const [code, setCode] = useState('');
  const [state, action, pending] = useActionState(toggleGoogleAuth, null);

  const { data, isPending } = useQuery({
    queryKey: ['google_auth'],
    queryFn: () => getKeyGoogleAuth(),
  });

  useEffect(() => {
    if (state?.success) {
      toast.success(t('success_text'));
      setOpen(false);
      setCode('');
    }
  }, [state, t]);

  const handleChange = (val: boolean) => {
    setChecked(val);
    setOpen(true);
  };

  const handleCopyCode = (value: string) => {
    navigator.clipboard.writeText(value);
    toast.success(t('coped'));
  };

  const handleCloseDialog = () => {
    setOpen(false);
    setChecked(totpUser);
  };
  const handleSubmit = () => {
    const data = {
      is_enabled: totpUser ? false : true,
      code,
    };
    startTransition(() => action(data));
  };

  return (
    <>
      <Switch
        size="sm"
        checked={checked}
        onCheckedChange={(val) => handleChange(val)}
      />
      <Dialog open={open}>
        <DialogContent
          close={false}
          className="w-full h-full md:h-auto md:w-[450px] overflow-y-auto"
        >
          <DialogHeader>
            <DialogTitle>{t('google_auth')}</DialogTitle>
          </DialogHeader>
          <AlertError errors={state?.error} />

          {!totpUser ? (
            <Card className="min-w-full">
              <CardContent className="lg:py-5 py-3">
                <div className="flex flex-wrap justify-between gap-5">
                  <div className="flex flex-col">
                    <span className="text-secondary-foreground text-sm">
                      {t('dialog_title')}
                    </span>
                  </div>
                  <div className="flex flex-wrap sm:flex-nowrap gap-5 lg:gap-7.5  w-full">
                    <div className="flex bg-center w-full p-5 lg:p-7 bg-no-repeat bg-[length:350px] border border-input rounded-xl border-dashed branding-bg">
                      <div className="flex h-40 flex-col place-items-center place-content-center text-center rounded-xl w-full">
                        {isPending ? (
                          <Spinner className="size-4 animate-spin" />
                        ) : (
                          <QRCode
                            style={{
                              height: 'auto',
                              maxWidth: '100%',
                              width: '100%',
                            }}
                            value={data?.qrUrl ?? '/defult'}
                          />
                        )}
                      </div>
                    </div>
                  </div>
                </div>
                <div className="border-t border-border my-7.5"></div>
                <div className="flex flex-wrap justify-between gap-5">
                  <div className="flex flex-col">
                    <div className="text-mono text-sm font-medium">
                      {t('dialog_code_active')}
                    </div>
                    <span className="text-secondary-foreground text-xs">
                      {t('dialog_code_active_description')}
                    </span>
                  </div>
                  <InputWrapper className="sm:max-w-full xl:max-w-96 w-full">
                    <Button
                      variant="dim"
                      mode="icon"
                      className="-me-2"
                      onClick={() => handleCopyCode(data?.secret)}
                    >
                      <Copy size={16} className="text-green-500" />
                    </Button>
                    <Input type="text" defaultValue={data?.secret} readOnly />
                  </InputWrapper>
                </div>
              </CardContent>
            </Card>
          ) : (
            <div className="flex flex-col items-center mt-2">
              <span className="bg-orange-50 h-11 flex items-center justify-center p-2 rounded-lg">
                <TriangleAlert size={30} color="oklch(75% 0.183 55.934)" />
              </span>
              <h2 className="text-orange-500 text-base mt-3 mb-2">
                {t('alert_notActive')}
              </h2>
            </div>
          )}
          <div className="mt-4">
            <div className="flex items-center justify-between mb-2">
              <Label>{t('dialog_title_verify_code')}</Label>
            </div>
            <Input value={code} onChange={(e) => setCode(e.target.value)} />
            <span className="text-xs text-primary">
              {t('dialog_title_verify_code_guide')}
            </span>
          </div>
          <DialogFooter>
            <Button
              type="button"
              variant="outline"
              className="mt-2 md:mt-0"
              onClick={handleCloseDialog}
            >
              {t('close')}
            </Button>
            <Button type="button" disabled={pending} onClick={handleSubmit}>
              {pending ? <Spinner className="size-4 animate-spin" /> : null}
              {t('register')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
};
