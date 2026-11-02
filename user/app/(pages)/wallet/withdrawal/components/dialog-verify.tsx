/* eslint-disable @typescript-eslint/no-explicit-any */

import {
    startTransition,
    useActionState,
    useContext,
    useEffect,
    useState,
} from 'react';
import Link from 'next/link';
import { WalletContext } from '@/store/wallet';
import { DialogDescription } from '@radix-ui/react-dialog';
import { useQueryClient } from '@tanstack/react-query';
import { TriangleAlert } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { withdrawal } from '@/services/wallet.services';
import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import DialogContent, {
    Dialog,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinners';

interface Props {
    data: any;
    open: boolean;
    setOpen: (op: boolean) => void;
    form: any;
    setTotalAmount: (total: number) => void;
}

export const DialogVerify: React.FC<Props> = ({
    data,
    open,
    setOpen,
    form,
    setTotalAmount,
}) => {
    const t = useTranslations('wallet.withdrawal');
    const { googleAuth, currencies, setAllCurrencies, selectedCoin } =
        useContext(WalletContext);
    const [code, setCode] = useState('');
    const [state, action, pending] = useActionState(withdrawal, null);
    const queryClient = useQueryClient();

    useEffect(() => {
        if (state?.success) {
            toast.success(t('success_text'));

            queryClient.invalidateQueries({
                queryKey: ['transactions'],
            });

            setAllCurrencies(
                currencies.map((currency) => {
                    if (currency.id !== +selectedCoin) {
                        return currency;
                    }

                    return {
                        ...currency,
                        user_asset: {
                            ...currency.user_asset,
                            spot: String(state.spot),
                            total: String(state.total),
                        },
                    };
                }),
            );

            queryClient.invalidateQueries({
                queryKey: ['wallet-currency'],
            });

            closeDialog();
            form.setValue('amount', '');
            setTotalAmount(0);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [state]);

    const closeDialog = () => {
        setOpen(false);
        setCode('');
    };

    const handleSubmit = () => {
        if (!googleAuth) return;

        const formData = { ...data, code };
        startTransition(() => action(formData));
    };

    return (
        <Dialog open={open} onOpenChange={closeDialog}>
            <DialogContent className="w-[450px]">
                <DialogHeader>
                    <DialogTitle>{t('confirm_transaction')}</DialogTitle>
                </DialogHeader>
                {!googleAuth ? (
                    <>
                        <Alert variant="warning" appearance="light" className="flex">
                            <AlertIcon>
                                <TriangleAlert className="text-yellow-600" />
                            </AlertIcon>
                            <AlertTitle className="text-xs leading-5">
                                {t('google_auth_required')}
                                <Link
                                    href="/settings-account#auth_two_factor"
                                    className="text-primary underline ms-1"
                                >
                                    {t('enable_google_auth')}
                                </Link>
                            </AlertTitle>
                        </Alert>
                        <DialogFooter>
                            <Button
                                type="button"
                                variant="outline"
                                onClick={closeDialog}
                            >
                                {t('close')}
                            </Button>
                        </DialogFooter>
                    </>
                ) : (
                    <>
                        <DialogDescription className="text-xs text-secondary-foreground mb-2">
                            *Google Authenticator
                        </DialogDescription>

                        <AlertError errors={state?.error} />

                        <div className="mt-4">
                            <div className="flex items-center justify-between">
                                <Label>{t('enter_code_google')}</Label>
                            </div>
                            <Input
                                value={code}
                                onChange={(e) => setCode(e.target.value)}
                                placeholder={t('enter_code_confirm')}
                            />
                        </div>
                        <DialogFooter>
                            <Button
                                type="button"
                                variant="outline"
                                onClick={closeDialog}
                            >
                                {t('close')}
                            </Button>
                            <Button
                                type="button"
                                disabled={pending}
                                onClick={handleSubmit}
                            >
                                {pending ? (
                                    <Spinner className="size-4 animate-spin" />
                                ) : null}
                                {t('register')}
                            </Button>
                        </DialogFooter>
                    </>
                )}
            </DialogContent>
        </Dialog>
    );
};
