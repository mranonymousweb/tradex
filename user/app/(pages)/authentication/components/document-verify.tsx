'use client';

import { Suspense } from 'react';
import { useQuery } from '@tanstack/react-query';
import { AlertCircle, CircleCheck, ExternalLink } from 'lucide-react';
import { useTranslations } from 'next-intl';
import QRCode from 'react-qr-code';
import { AuthenticationSettings, DocumentVerifyData } from '@/types/auth';
import { getDocumentVerify } from '@/services/auth.services';
import { Alert, AlertIcon } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinners';

const getVerifyUrl = (data?: DocumentVerifyData) =>
    data?.url ?? data?.link ?? data?.redirect_url ?? '';

export const DocumentVerify = ({
    config,
}: {
    config: AuthenticationSettings;
}) => {
    const t = useTranslations('authentication');

    const { data, isPending, isError } = useQuery({
        queryKey: ['document-verify'],
        queryFn: () => getDocumentVerify(),
    });
    const verifyUrl = getVerifyUrl(data);

    return (
        <Card className="w-full mt-5">
            <CardContent className="p-6">
                {config?.description && (
                    <Alert variant="destructive" appearance="light">
                        <AlertIcon>
                            <AlertCircle />
                        </AlertIcon>
                        {config.description}
                    </Alert>
                )}

                <Suspense>
                    <CardHeader className="px-0 md:px-5 mb-5 pb-4 md:pb-0">
                        <div className="flex flex-col md:flex-row gap-2">
                            <div className="flex items-center gap-x-2 text-primary">
                                <CircleCheck color="#1379f0" />
                                <span>{t('info_complete')}</span>
                                <span className="hidden md:inline-block h-px w-12 xl:w-32 bg-primary"></span>
                            </div>
                            <div className="flex items-center gap-x-2 text-primary">
                                <div className="w-5 h-5 rounded-full bg-primary text-background flex items-center justify-center text-xs">
                                    2
                                </div>
                                <span>{t('send_document')}</span>
                                <span className="hidden md:inline-block h-px w-12 xl:w-32 bg-primary"></span>
                            </div>
                            <div className="flex items-center gap-x-2 text-secondary-foreground">
                                <div className="w-5 h-5 rounded-full bg-muted-foreground text-background flex items-center justify-center text-xs">
                                    3
                                </div>
                                <span>{t('check_final')}</span>
                            </div>
                        </div>
                    </CardHeader>

                    <div className="flex flex-col items-center gap-6">
                        <p className="text-sm md:text-base font-medium text-center mt-4">
                            {t('document_verify_title')}
                        </p>

                        <div className="flex max-w-sm min-w-xs border py-7 rounded-xl branding-bg dark:bg-foreground">
                            <div className="flex h-48 w-full flex-col items-center justify-center rounded-xl ">
                                {isPending ? (
                                    <Spinner className="size-6 animate-spin" />
                                ) : verifyUrl ? (
                                    <QRCode
                                        style={{
                                            height: 'auto',
                                            maxWidth: '100%',
                                            width: '100%',
                                        }}
                                        value={verifyUrl}
                                    />
                                ) : (
                                    <span className="text-sm text-secondary-foreground">
                                        {t('document_verify_unavailable')}
                                    </span>
                                )}
                            </div>
                        </div>

                        {isError && (
                            <Alert variant="destructive" appearance="light">
                                <span className="text-destructive">
                                    {t('document_verify_error')}
                                </span>
                            </Alert>
                        )}

                        <p className="text-xs text-secondary-foreground text-center">
                            {t('document_verify_scan')}
                        </p>
                    </div>

                    <CardFooter className="flex justify-center px-0 pt-6">
                        <Button
                            asChild
                            disabled={!verifyUrl || isPending}
                            className="gap-2"
                        >
                            <a
                                href={verifyUrl || undefined}
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <ExternalLink size={16} />
                                {t('document_verify_button')}
                            </a>
                        </Button>
                    </CardFooter>
                </Suspense>
            </CardContent>
        </Card>
    );
};
