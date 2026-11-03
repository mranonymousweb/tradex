import {
    Dispatch,
    SetStateAction,
    startTransition,
    useActionState,
    useEffect,
    useState,
} from 'react';
import dynamic from 'next/dynamic';
import Link from 'next/link';
import { zodResolver } from '@hookform/resolvers/zod';
import { AlertCircle, ArrowLeft } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { useGoogleReCaptcha } from 'react-google-recaptcha-v3';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import { z } from 'zod';
import { AlertError } from '@/hooks/alert-error';
import { forgetPassword } from '@/services/auth.services';
import { Alert, AlertIcon } from '@/components/ui/alert';
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
import { verifyRecaptchaToken } from '@/lib/verify-recaptcha-client';
import { recaptchaKey } from '../../config';

const ValidateRecaptchaSlider = dynamic(
    () => import('@/hooks/use-recaptcha-slider'),
    { ssr: false },
);

interface Info {
    step: number;
    username: string;
    mobile: string;
    email: string;
    userId: string;
}

interface Props {
    setUserForm: Dispatch<SetStateAction<Info>>;
    userForm: Info;
}

const Step1: React.FC<Props> = ({ userForm, setUserForm }) => {
    const t = useTranslations('auth');
    const [showRecaptcha, setShowRecaptcha] = useState(false);
    const [showRecaptchaPuzzle, setShowRecaptchaPuzzle] = useState(false);
    const [state, action, pending] = useActionState(forgetPassword, null);
    const { executeRecaptcha } = useGoogleReCaptcha();

    useEffect(() => {
        if (state?.success) {
            setUserForm({
                ...userForm,
                step: 2,
                mobile: state.data.mobile,
                email: state.data.email,
                username: state.username,
                userId: state.data.userId,
            });
        }
    }, [state, setUserForm, userForm]);

    const formSchema = z.object({
        username: z.string().min(1, { message: t('enter_user_name') }),
        step: z.number(),
    });

    const form = useForm<z.infer<typeof formSchema>>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            step: userForm.step,
            username: '',
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

                            <div className="text-center space-y-1 pb-3">
                                <h1 className="text-xl font-semibold tracking-tight">
                                    {t('forget_password')}
                                </h1>
                                <p className="text-xs text-muted-foreground">
                                    {t('desciption_forget_password')}
                                </p>
                            </div>

                            <FormField
                                control={form.control}
                                name="username"
                                render={({ field }) => (
                                    <FormItem>
                                        <FormLabel>{t('user_name')}</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="text"
                                                placeholder={t('email_phone')}
                                                {...field}
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />

                            <Button
                                type="submit"
                                className="w-full"
                                disabled={pending || showRecaptcha}
                            >
                                {pending || showRecaptcha ? (
                                    <Spinner className="size-4 animate-spin" />
                                ) : null}
                                {t('add')}
                            </Button>

                            <div className="space-y-3">
                                <Button
                                    type="button"
                                    variant="outline"
                                    className="w-full"
                                    asChild
                                >
                                    <Link href="/signin">
                                        <ArrowLeft className="size-3.5" /> {t('back')}
                                    </Link>
                                </Button>
                            </div>
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
};
export default Step1;
