'use client';

import {
    startTransition,
    Suspense,
    useActionState,
    useEffect,
} from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { reformatDate } from '@/helper/reformatDate';
import { zodResolver } from '@hookform/resolvers/zod';
import { useQuery } from '@tanstack/react-query';
import { AlertCircle, CircleCheck } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { userAuthentication } from '@/services/auth.services';
import { metaData } from '@/services/metaData.services';
import { Alert, AlertIcon } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Calender } from '@/components/ui/date-picker';
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
import { FormSchema, FormSchemaType } from './form-schema';
import { StatusAuth } from '@/types/auth';

interface Config {
    name?: string | null;
    family?: string | null;
    national_code?: string | null;
    birthday?: string | null;
    description?: string | null;
    status: 'waiting' | 'accepted' | 'rejected';
}

interface Props {
    ip: string;
    config: Config;
    status: StatusAuth;
}

export default function FormVerify({
    ip,
    config,
    status,
}: Props) {
    const t = useTranslations('authentication');
    const router = useRouter();
    const [state, action, pending] = useActionState(userAuthentication, null);
    const { data } = useQuery({
        queryKey: ['meta-data'],
        queryFn: () => metaData(),
    });

    useEffect(() => {
        if (state?.success) {
            toast.success(state.message);
            router.refresh();
        }
    }, [state, router]);

    const form = useForm<FormSchemaType>({
        resolver: zodResolver(FormSchema(t)),
        defaultValues: {
            name: config.name ?? '',
            family: config.family ?? '',
            national_code: config.national_code ?? '',
            birthday: config.birthday ?? '',
            ip_address: '',
            accept_terms: false,
            accept_rules: false,
        },
    });

    const acceptTerms = form.watch('accept_terms');
    const acceptRules = form.watch('accept_rules');

    async function onSubmit(values: FormSchemaType) {
        const data = {
            name: values.name,
            family: values.family,
            national_code: values.national_code,
            birthday: reformatDate(values.birthday),
            ip_address: ip,
        };
        startTransition(() => action(JSON.stringify(data)));
    }

    return (
        <Card className="w-full mt-5">
            <CardContent className="p-6">
                <Suspense>
                    <Form {...form}>
                        <form
                            onSubmit={form.handleSubmit(onSubmit)}
                            className="block w-full space-y-5"
                        >
                            <CardHeader className="px-0 md:px-5 mb-5 pb-4 md:pb-0">
                                <div className="flex flex-col md:flex-row gap-2">
                                    <div className="flex items-center gap-x-2 text-primary">
                                        <CircleCheck color="#1379f0" />
                                        <span>{t('info_complete')}</span>
                                        <span className="hidden md:inline-block h-px w-12 xl:w-32 bg-primary"></span>
                                    </div>
                                    <div className="flex items-center gap-x-2">
                                        <div className="w-5 h-5 rounded-full bg-muted-foreground text-background flex items-center justify-center text-xs">
                                            2
                                        </div>
                                        <span>{t('send_document')}</span>
                                        <span className="hidden md:inline-block h-px w-12 xl:w-32 bg-muted-foreground"></span>
                                    </div>
                                    <div className="flex items-center gap-x-2">
                                        <div className="w-5 h-5 rounded-full bg-muted-foreground text-background flex items-center justify-center text-xs">
                                            3
                                        </div>
                                        <span>{t('check_final')}</span>
                                    </div>
                                </div>
                            </CardHeader>
                            {state && 'error' in state && state.error && (
                                <AlertError errors={state.error} />
                            )}

                            {/*Description Erorr*/}
                            {config?.description && (
                                <Alert variant="destructive" className='mb-12' appearance="light">
                                    <AlertIcon>
                                        <AlertCircle />
                                    </AlertIcon>
                                    {config?.description}
                                </Alert>
                            )}

                            {
                                status === StatusAuth.WAITING && (
                                    <Alert variant="warning" className="mt-4 mb-12" appearance="light">
                                        <AlertIcon></AlertIcon>
                                        {t('awaiting_auth')}
                                    </Alert>
                                )
                            }

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <FormField
                                    control={form.control}
                                    name="name"
                                    render={({ field }) => (
                                        <FormItem>
                                            <FormLabel>{t('name')}</FormLabel>
                                            <FormControl>
                                                <Input placeholder={t('placeholder_name')} {...field} />
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    )}
                                />

                                <FormField
                                    control={form.control}
                                    name="family"
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
                                    name="national_code"
                                    render={({ field }) => (
                                        <FormItem>
                                            <FormLabel>{t('national_code')}</FormLabel>
                                            <FormControl>
                                                <Input
                                                    placeholder={t('placeholder_national_code')}
                                                    {...field}
                                                />
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    )}
                                />

                                <FormField
                                    control={form.control}
                                    name="birthday"
                                    render={({ field }) => (
                                        <FormItem>
                                            <FormLabel>{t('birth_date')}</FormLabel>
                                            <FormControl>
                                                <Calender {...field} />
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    )}
                                />
                            </div>

                            <div className="flex flex-col gap-3">
                                <FormField
                                    control={form.control}
                                    name="accept_terms"
                                    render={({ field }) => (
                                        <FormItem>
                                            <FormControl>
                                                <div className="flex flex-wrap items-center gap-x-2.5 gap-y-1">
                                                    <Checkbox
                                                        id="accept_terms"
                                                        className="border-primary"
                                                        checked={field.value}
                                                        onCheckedChange={(checked) =>
                                                            field.onChange(!!checked)
                                                        }
                                                    />
                                                    <label
                                                        htmlFor="accept_terms"
                                                        className="leading-none text-sm font-normal text-secondary-foreground"
                                                    >
                                                        {t('accept_terms_prefix')}
                                                    </label>
                                                    <Link
                                                        href={data?.url?.user_rule ?? '#'}
                                                        target="_blank"
                                                        className="-ms-0.5 text-sm font-semibold text-foreground hover:text-primary"
                                                    >
                                                        {t('accept_terms_link')}
                                                    </Link>
                                                    <span className="text-sm font-normal text-secondary-foreground">
                                                        {t('accept_terms_suffix')}
                                                    </span>
                                                </div>
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    )}
                                />

                                <FormField
                                    control={form.control}
                                    name="accept_rules"
                                    render={({ field }) => (
                                        <FormItem>
                                            <FormControl>
                                                <div className="flex flex-wrap items-center gap-x-2.5 gap-y-1">
                                                    <Checkbox
                                                        id="accept_rules"
                                                        className="border-primary"
                                                        checked={field.value}
                                                        onCheckedChange={(checked) =>
                                                            field.onChange(!!checked)
                                                        }
                                                    />
                                                    <label
                                                        htmlFor="accept_rules"
                                                        className="leading-none text-sm font-normal text-secondary-foreground"
                                                    >
                                                        {t('accept_rules_prefix')}
                                                    </label>
                                                    <Link
                                                        href={data?.url?.user_rule ?? '#'}
                                                        target="_blank"
                                                        className="-ms-0.5 text-sm font-semibold text-foreground hover:text-primary"
                                                    >
                                                        {t('accept_rules_link')}
                                                    </Link>
                                                    <span className="text-sm font-normal text-secondary-foreground">
                                                        {t('accept_rules_suffix')}
                                                    </span>
                                                </div>
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    )}
                                />
                            </div>

                            <CardFooter className="flex justify-end px-0 items-center">
                                {status === StatusAuth.WAITING ? (
                                    <Button type="button" variant="secondary">
                                        {t('under_review')}
                                    </Button>
                                ) : (
                                    <Button
                                        type="submit"
                                        disabled={pending || !acceptTerms || !acceptRules}
                                    >
                                        {pending ? (
                                            <Spinner className="size-4 animate-spin" />
                                        ) : null}
                                        {t('confirm')}
                                    </Button>
                                )}
                            </CardFooter>
                        </form>
                    </Form>
                </Suspense>
            </CardContent>
        </Card>
    );
}
