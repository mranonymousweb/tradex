'use client';

import {
    startTransition,
    Suspense,
    useActionState,
    useEffect,
    useState,
} from 'react';
import Link from 'next/link';
import { zodResolver } from '@hookform/resolvers/zod';
import { Eye, EyeOff } from 'lucide-react';
import { useForm } from 'react-hook-form';
import PhoneInput from 'react-phone-input-2';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import 'react-phone-input-2/lib/style.css';
import dynamic from 'next/dynamic';
import { useRouter, useSearchParams } from 'next/navigation';
import { useQuery } from '@tanstack/react-query';
import { useLocale, useTranslations } from 'next-intl';
import { useGoogleReCaptcha } from 'react-google-recaptcha-v3';
import { toast } from 'sonner';
import { setData } from '@/lib/storage';
import { AlertError } from '@/hooks/alert-error';
import { signUpForm } from '@/services/auth.services';
import { metaData } from '@/services/metaData.services';
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
import { getSignupSchema, SignupSchemaType } from '../forms/signup-schema';
import { verifyRecaptchaToken } from '@/lib/verify-recaptcha-client';
import { recaptchaKey } from '../config';

const ValidateRecaptchaSlider = dynamic(
    () => import('@/hooks/use-recaptcha-slider'),
    { ssr: false },
);

export default function Page() {
    const t = useTranslations('auth');
    const locale = useLocale();
    const searchParams = useSearchParams();
    const refral = searchParams.get('ref');
    const [passwordVisible, setPasswordVisible] = useState(false);
    const [phone, setPhone] = useState('');
    const [showRecaptcha, setShowRecaptcha] = useState(false);
    const [showRecaptchaPuzzle, setShowRecaptchaPuzzle] = useState(false);
    const [state, action, pending] = useActionState(signUpForm, null);

    const { executeRecaptcha } = useGoogleReCaptcha();

    const router = useRouter();
    const LANG_PHONE = locale === 'fa' ? 'ir' : 'us';

    const { data } = useQuery({
        queryKey: ['meta-data'],
        queryFn: () => metaData(),
    });

    useEffect(() => {
        if (state?.success) {
            setData('time', 120);
            if (!state?.verifyEmail) {
                router.push('/verify-account');
            } else {
                router.push('/verify-email-phone');
            }
        }
    }, [state, router]);

    const form = useForm<SignupSchemaType>({
        resolver: zodResolver(getSignupSchema(t)),
        defaultValues: {
            firstName: '',
            lastName: '',
            mobile: '',
            countryCode: '',
            refId: refral ?? '',
            email: '',
            password: '',
            accept: false,
        },
    });

    const handlePhoneChange = (value: string, data: { dialCode: string }) => {
        setPhone(value);
        form.setValue('mobile', `+${value}`);
        form.setValue('countryCode', `+${data.dialCode}`);
    };

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
            <Card className="w-full max-w-[40rem]">
                <CardContent className="p-6">
                    {typeof state === 'object' && state?.error && (
                        <AlertError errors={state?.error} />
                    )}

                    <Suspense>
                        <Form {...form}>
                            <form onSubmit={handleSubmit} className="block w-full space-y-5">
                                <div className="space-y-1.5 pb-3">
                                    <h1 className="text-2xl font-semibold tracking-tight text-center">
                                        {t('register')}
                                    </h1>
                                </div>

                                <div className="flex flex-col gap-3.5 md:max-w-2/3 mx-auto">
                                    <Button variant="outline" type="button">
                                        {t('welcome')}
                                    </Button>
                                </div>

                                <div className="relative py-1.5">
                                    <div className="absolute inset-0 flex items-center">
                                        <span className="w-full border-t" />
                                    </div>
                                    <div className="relative flex justify-center text-xs uppercase">
                                        <span className="bg-background px-2 text-muted-foreground">
                                            {t('information_user')}
                                        </span>
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    <FormField
                                        control={form.control}
                                        name="firstName"
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormLabel>{t('name')}</FormLabel>
                                                <FormControl>
                                                    <Input
                                                        placeholder={t('placeholder_name')}
                                                        {...field}
                                                    />
                                                </FormControl>
                                                <FormMessage />
                                            </FormItem>
                                        )}
                                    />

                                    <FormField
                                        control={form.control}
                                        name="lastName"
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormLabel>{t('family')}</FormLabel>
                                                <FormControl>
                                                    <Input
                                                        placeholder={t('placeholder_family')}
                                                        {...field}
                                                    />
                                                </FormControl>
                                                <FormMessage />
                                            </FormItem>
                                        )}
                                    />

                                    <FormField
                                        control={form.control}
                                        name="mobile"
                                        render={() => (
                                            <FormItem>
                                                <FormLabel>{t('phone')}</FormLabel>
                                                <PhoneInput
                                                    country={LANG_PHONE}
                                                    placeholder={t('placeholder_phone')}
                                                    value={phone}
                                                    onChange={handlePhoneChange}
                                                    inputClass="dark:!bg-transparent"
                                                />
                                                <FormMessage />
                                            </FormItem>
                                        )}
                                    />

                                    <FormField
                                        control={form.control}
                                        name="email"
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormLabel>{t('email')}</FormLabel>
                                                <FormControl>
                                                    <Input
                                                        placeholder={t('placeholder_email')}
                                                        {...field}
                                                    />
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
                                                <FormLabel>{t('password')}</FormLabel>
                                                <div className="relative">
                                                    <Input
                                                        placeholder={t('placeholder_password')}
                                                        type={passwordVisible ? 'text' : 'password'}
                                                        {...field}
                                                    />
                                                    <Button
                                                        type="button"
                                                        variant="ghost"
                                                        mode="icon"
                                                        size="sm"
                                                        onClick={() => setPasswordVisible(!passwordVisible)}
                                                        className="absolute end-0 top-1/2 -translate-y-1/2 h-7 w-7 me-1.5 bg-transparent!"
                                                        aria-label={
                                                            passwordVisible
                                                                ? 'Hide password'
                                                                : 'Show password'
                                                        }
                                                    >
                                                        {!passwordVisible ? (
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

                                    <FormField
                                        control={form.control}
                                        name="refId"
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormLabel>
                                                    {t('refral_code')} ({t('optional')})
                                                </FormLabel>
                                                <FormControl>
                                                    <Input
                                                        placeholder={t('placeholder_refral')}
                                                        {...field}
                                                    />
                                                </FormControl>
                                                <FormMessage />
                                            </FormItem>
                                        )}
                                    />
                                </div>

                                <div className="flex items-center space-x-2">
                                    <FormField
                                        control={form.control}
                                        name="accept"
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormControl>
                                                    <div className="flex items-center gap-2.5">
                                                        <Checkbox
                                                            id="accept"
                                                            checked={field.value}
                                                            onCheckedChange={(checked) =>
                                                                field.onChange(!!checked)
                                                            }
                                                        />
                                                        <label
                                                            htmlFor="accept"
                                                            className="text-sm leading-none text-muted-foreground"
                                                        >
                                                            {t('accept')}
                                                        </label>
                                                        <Link
                                                            href={data?.url?.user_rule ?? '#'}
                                                            target="_blank"
                                                            className="-ms-0.5 text-sm font-semibold text-foreground hover:text-primary"
                                                        >
                                                            {t('text_rule')}
                                                        </Link>
                                                    </div>
                                                </FormControl>
                                                <FormMessage />
                                            </FormItem>
                                        )}
                                    />
                                </div>

                                <div className="flex flex-col gap-2.5 md:max-w-1/2 mx-auto">
                                    <Button type="submit" disabled={pending || showRecaptcha}>
                                        {pending || showRecaptcha ? (
                                            <Spinner className="size-4 animate-spin" />
                                        ) : null}
                                        {t('add')}
                                    </Button>
                                </div>

                                <div className="text-sm text-muted-foreground text-center">
                                    {t('not_create_account_before')}{' '}
                                    <Link
                                        href="/signin"
                                        className="text-sm font-semibold text-foreground hover:text-primary"
                                    >
                                        {t('login')}
                                    </Link>
                                </div>
                                <div className="flex justify-center">
                                    <WrapperLangDropdownAuth />
                                </div>
                            </form>
                        </Form>
                    </Suspense>
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
