'use client';

import { startTransition, useActionState, useEffect, useState } from 'react';
import dynamic from 'next/dynamic';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { zodResolver } from '@hookform/resolvers/zod';
import { Eye, EyeOff } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { useGoogleReCaptcha } from 'react-google-recaptcha-v3';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import { setData } from '@/lib/storage';
import { AlertError } from '@/hooks/alert-error';
import { loginForm } from '@/services/auth.services';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinners';
import { WrapperLangDropdownAuth } from '@/components/partials/topbar/auth-warpper-lang';
import { getSigninSchema, SigninSchemaType } from '../forms/signin-schema';
import { verifyRecaptchaToken } from '@/lib/verify-recaptcha-client';
import { recaptchaKey } from '../config';

const ValidateRecaptchaSlider = dynamic(
    () => import('@/hooks/use-recaptcha-slider'),
    { ssr: false },
);

export default function Page() {
    const t = useTranslations('auth');
    const [passwordVisible, setPasswordVisible] = useState(false);
    const [showRecaptcha, setShowRecaptcha] = useState(false);
    const [showRecaptchaPuzzle, setShowRecaptchaPuzzle] = useState(false);
    const [state, action, pending] = useActionState(loginForm, null);
    const { executeRecaptcha } = useGoogleReCaptcha();
    const router = useRouter();


    useEffect(() => {
        if (state?.success) {
            router.push('/verify-account');
            setData('time', 120);
        }
    }, [state, router]);

    const form = useForm<SigninSchemaType>({
        resolver: zodResolver(getSigninSchema(t)),
        defaultValues: {
            username: '',
            password: '',
        },
    });


    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        const result = await form.trigger();
        if (!result) return;

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
        const values = form.getValues();
        startTransition(() => action(values));
        setShowRecaptcha(false);
    };

    return (
        <>
            <Card className="w-full max-w-[400px]">
                <CardContent className="p-6">
                    <Form {...form}>
                        <form onSubmit={handleSubmit} className="block w-full space-y-5">
                            <div className="flex flex-col items-center space-y-1.5">
                                <h1 className="text-2xl font-semibold tracking-tight text-center mb-5">
                                    {t('login')}
                                </h1>
                                <WrapperLangDropdownAuth />
                            </div>

                            {state?.error && <AlertError errors={state.error} />}

                            <div className="relative py-1.5">
                                <div className="absolute inset-0 flex items-center">
                                    <span className="w-full border-t" />
                                </div>
                                <div className="relative flex justify-center text-xs uppercase">
                                    <span className="bg-background px-2 text-muted-foreground">
                                        {t('email_password')}
                                    </span>
                                </div>
                            </div>

                            <FormField
                                control={form.control}
                                name="username"
                                render={({ field }) => (
                                    <FormItem>
                                        <FormLabel>{t('user_name')}</FormLabel>
                                        <FormControl>
                                            <Input placeholder={t('email_phone')} {...field} />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />

                            <FormField
                                control={form.control}
                                name="password"
                                render={({ field }) => (
                                    <FormItem>
                                        <div className="flex justify-between items-center gap-2.5">
                                            <FormLabel>{t('password')}</FormLabel>
                                            <Link
                                                href="/reset-password"
                                                className="text-sm font-semibold text-foreground hover:text-primary"
                                            >
                                                {t('forget_password')}
                                            </Link>
                                        </div>
                                        <div className="relative">
                                            <Input
                                                placeholder={t('placeholder_forget_password')}
                                                type={passwordVisible ? 'text' : 'password'} // Toggle input type
                                                {...field}
                                            />
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                mode="icon"
                                                size="sm"
                                                onClick={() => setPasswordVisible(!passwordVisible)} // Toggle visibility
                                                className="absolute end-0 top-1/2 -translate-y-1/2 h-7 w-7 me-1.5 bg-transparent!"
                                                aria-label={
                                                    passwordVisible ? 'Hide password' : 'Show password'
                                                }
                                            >
                                                {passwordVisible ? (
                                                    <EyeOff className="text-muted-foreground" />
                                                ) : (
                                                    <Eye className="text-muted-foreground" />
                                                )}
                                            </Button>
                                        </div>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />

                            <div className="flex flex-col gap-2.5">
                                <Button type="submit" disabled={pending || showRecaptcha}>
                                    {pending || showRecaptcha ? (
                                        <Spinner className="size-4 animate-spin" />
                                    ) : null}
                                    {t('login')}
                                </Button>
                            </div>

                            <p className="text-sm text-muted-foreground text-center">
                                {t('not_create_account')}{' '}
                                <Link
                                    href="/signup"
                                    className="text-sm font-semibold text-foreground hover:text-primary"
                                >
                                    {t('register')}
                                </Link>
                            </p>
                        </form>
                    </Form>
                </CardContent>
            </Card>
            {showRecaptchaPuzzle && (
                <ValidateRecaptchaSlider
                    setShowRecaptchaPuzzle={setShowRecaptchaPuzzle}
                    onSubmit={handleVerifiedSubmit}
                />
            )}
        </>
    );
}
