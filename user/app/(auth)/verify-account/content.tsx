'use client';

import { startTransition, useActionState, useEffect, useState } from 'react';
import dynamic from 'next/dynamic';
import { getBrowser, getDeviceType } from '@/helper/navigator';
import { useTranslations } from 'next-intl';
import { useGoogleReCaptcha } from 'react-google-recaptcha-v3';
import { toast } from 'sonner';
import { User } from '@/types/auth';
import { AlertError } from '@/hooks/alert-error';
import { verify } from '@/services/auth.services';
import { getIP } from '@/services/ip-address.services';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinners';
import { VerifyAccountGoogle } from './components/verify-code-google';
import { VerifyAccountCode } from './components/verify-code-sms';
import { verifyRecaptchaToken } from '@/lib/verify-recaptcha-client';
import { recaptchaKey } from '../config';

const ValidateRecaptchaSlider = dynamic(
    () => import('@/hooks/use-recaptcha-slider'),
    { ssr: false },
);

export const Content = ({ user }: { user: User }) => {
    const t = useTranslations('auth');
    const [input, setInput] = useState('');
    const [ip, setIp] = useState('127.0.0.1');
    const [showRecaptcha, setShowRecaptcha] = useState(false);
    const [showRecaptchaPuzzle, setShowRecaptchaPuzzle] = useState(false);
    const { executeRecaptcha } = useGoogleReCaptcha();

    const [state, action, pending] = useActionState(verify, null);

    useEffect(() => {
        const fetchData = async () => {
            const ip = await getIP();
            setIp(ip);
        };
        fetchData();
    }, []);

    useEffect(() => {
        const isReady =
            input.length === 6 && !pending && !showRecaptcha && !showRecaptchaPuzzle;

        if (!isReady) return;

        const fakeEvent = {
            preventDefault: () => { },
        } as React.FormEvent<HTMLFormElement>;

        handleSubmit(fakeEvent);
    }, [input]);

    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        // Check reCAPTCHA site key
        if (!recaptchaKey) {
            setShowRecaptchaPuzzle(true);
            console.log('Recaptcha keys missing, bypassing verification');
            return;
        }

        //setup Recaptch
        if (!executeRecaptcha) {
            setShowRecaptchaPuzzle(true);
            console.log('Execute recaptcha not yet available');
            return;
        }
        setShowRecaptcha(true);

        try {
            const gReCaptchaToken = await executeRecaptcha('submit');
            const verification = await verifyRecaptchaToken(gReCaptchaToken);
            if (verification.ok) {
                handleVerifiedSubmit();
                return;
            }
        } catch {
            // fall through to slider captcha
        }
        setShowRecaptcha(false);
        setShowRecaptchaPuzzle(true);
        toast.error('Google Recaptch Failed');
    };

    const handleVerifiedSubmit = async () => {
        const data = {
            codeSms: input,
            platform: navigator.platform,
            device: getDeviceType(),
            browser: getBrowser(),
            ip: ip ?? '127.0.0.1',
            userId: user?.id,
        };
        startTransition(() => action(data));
        setShowRecaptcha(false);
    };

    return (
        <>
            <Card className="w-full max-w-[400px]">
                <div className="flex items-center justify-center grow bg-center bg-no-repeat">
                    <div className="card max-w-[380px] w-full" id="2fa_form">
                        <form
                            onSubmit={handleSubmit}
                            className="card-body flex flex-col gap-5 p-10"
                        >
                            <AlertError errors={state?.error} />

                            <div className="flex justify-center">
                                <i className="ki-filled text-5xl ki-shield-tick text-primary"></i>
                            </div>
                            {user.google_2fa_is_enabled ? (
                                <VerifyAccountGoogle input={input} setInput={setInput} />
                            ) : (
                                <VerifyAccountCode
                                    input={input}
                                    setInput={setInput}
                                    user={user}
                                />
                            )}

                            <Button type="submit" disabled={pending || showRecaptcha}>
                                {pending || showRecaptcha ? (
                                    <Spinner className="size-4 animate-spin" />
                                ) : null}
                                {t('add')}
                            </Button>
                        </form>
                    </div>
                </div>
            </Card>

            {showRecaptchaPuzzle && (
                <ValidateRecaptchaSlider
                    setShowRecaptchaPuzzle={setShowRecaptchaPuzzle}
                    onSubmit={handleVerifiedSubmit}
                />
            )}
        </>
    );
};
