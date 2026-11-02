'use client';

import { startTransition, useActionState, useEffect, useState } from 'react';
import { RiCheckboxCircleFill } from '@remixicon/react';
import { AlertCircle } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { getData, setData } from '@/lib/storage';
import { AlertError } from '@/hooks/alert-error';
import { recentCodeRegister } from '@/services/auth.services';
import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';
import { Spinner } from '@/components/ui/spinners';

export const RecentCodeRegister = () => {
    const t = useTranslations('auth');
    const [sconds, setSconds] = useState(0);
    const [state, action, pending] = useActionState(recentCodeRegister, null);

    useEffect(() => {
        if (state?.success) {
            setData('time', 120);
            setSconds(120);
            const message = t('success_text');
            toast.custom(
                (t) => (
                    <Alert
                        variant="success"
                        icon="success"
                        close={false}
                        onClose={() => toast.dismiss(t)}
                    >
                        <AlertIcon>
                            <RiCheckboxCircleFill />
                        </AlertIcon>
                        <AlertTitle>{message}</AlertTitle>
                    </Alert>
                ),
                {
                    position: 'top-center',
                },
            );
        }
    }, [state, t]);

    useEffect(() => {
        const timer = getData('time');
        if (Number(timer) <= 0) return;

        const intervalId = setInterval(() => {
            const newtime = Number(timer) - 1;
            setSconds(newtime);
            setData('time', newtime);
        }, 1000);

        return () => clearInterval(intervalId);
    }, [sconds]);

    return (
        <>
            {state?.error && (
                <Alert variant="destructive">
                    <AlertIcon>
                        <AlertCircle />
                    </AlertIcon>
                    <div>
                        <AlertError errors={state?.error} />
                    </div>
                </Alert>
            )}
            <span className="text-xs text-gray-600">
                {t('not_received_code')} ({sconds}s)
            </span>
            <button
                type="button"
                className="text-xs flex items-center gap-x-1 font-semibold text-primary hover:text-primary cursor-pointer"
                onClick={async () => {
                    await startTransition(() => action());
                }}
                disabled={sconds > 0 || pending}
            >
                {pending ? <Spinner className="size-4 animate-spin" /> : null}
                {t('resend_code')}
            </button>
        </>
    );
};
