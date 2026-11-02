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
import { VerifyEmail } from './components/verify-email';
import { VerifyPhone } from './components/verify-phone';
import { RecentCode } from '../verify-account/components/RecentCode';
import { verifyRecaptchaToken } from '@/lib/verify-recaptcha-client';
import { recaptchaKey } from '../config';
import { RecentCodeRegister } from './components/RecentCode';

const ValidateRecaptchaSlider = dynamic(
    () => import('@/hooks/use-recaptcha-slider'),
    { ssr: false },
);

export const Content = ({ user }: { user: User }) => {
    const t = useTranslations('auth');
    const [emailCode, setEmailCode] = useState('');
    const [phoneCode, setPhoneCode] = useState('');
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
            emailCode.length === 6 &&
            phoneCode.length === 6 &&
            !pending &&
            !showRecaptcha &&
            !showRecaptchaPuzzle;

        if (!isReady) return;

        const fakeEvent = {
            preventDefault: () => { },
        } as React.FormEvent<HTMLFormElement>;

        handleSubmit(fakeEvent);
    }, [emailCode, phoneCode]);

    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (emailCode.length !== 6 || phoneCode.length !== 6) return;

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
            codeEmail: emailCode,
            codeSms: phoneCode,
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
            <Card className="w-full max-w-[500px]">
                <div className="flex items-center justify-center grow bg-center bg-no-repeat">
                    <div className="card max-w-[450px] w-full" id="verify_email_phone_form">
                        <form
                            onSubmit={handleSubmit}
                            className="card-body flex flex-col gap-5 p-2 md:p-8"
                        >
                            <AlertError errors={state?.error} />

                            <div className="flex flex-col items-center text-center gap-2">
                                <i className="ki-filled text-5xl ki-shield-tick text-primary"></i>
                                <h3 className="text-lg font-semibold text-foreground">
                                    {t('confirm_account')}
                                </h3>
                                <p className="text-2sm text-muted-foreground font-medium">
                                    {t('confirm_account_desc')}
                                </p>
                            </div>

                            <VerifyEmail
                                emailCode={emailCode}
                                setEmailCode={setEmailCode}
                                user={user}
                            />

                            <VerifyPhone
                                phoneCode={phoneCode}
                                setPhoneCode={setPhoneCode}
                                user={user}
                            />
                            <div className="flex items-center gap-x-2 justify-center font-medium">
                                <RecentCodeRegister />
                            </div>

                            <Button
                                type="submit"
                                disabled={
                                    pending ||
                                    showRecaptcha ||
                                    emailCode.length !== 6 ||
                                    phoneCode.length !== 6
                                }
                            >
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
