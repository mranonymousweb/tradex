/* eslint-disable @typescript-eslint/no-explicit-any */

import { useState } from 'react';
import { CircleAlert, CircleCheck, CircleX } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { AuthenticationSettings } from '@/types/auth';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinners';
import { DialogVideoGuide } from './dialog-video';
import RecorderVideo from './RecorderVideo';

export const UploadVideo = ({
    setVideo,
    config,
    verifiedVideo = false,
}: {
    setVideo: any;
    config: AuthenticationSettings;
    verifiedVideo?: boolean;
}) => {
    const t = useTranslations('authentication');
    const [agreementText, setAgreementText] = useState(false);

    return (
        <Card className="w-80">
            <CardHeader className="py-1">
                <CardTitle className="text-sm">
                    {!verifiedVideo ? <DialogVideoGuide config={config} /> : (
                        <span>{t('upload_agreement_video')}</span>
                    )}
                </CardTitle>
                {!verifiedVideo && (
                    <Button
                        type="button"
                        variant="outline"
                        className="flex items-center"
                        onClick={() => setAgreementText(true)}
                    >
                        <i className="ki-outline ki-tablet-text-up"></i>
                        <span>{t('text_undertaking')}</span>
                    </Button>
                )}
            </CardHeader>
            {!verifiedVideo && agreementText &&
                (!config ? (
                    <Spinner />
                ) : (
                    <p className="border-b p-2 text-justify text-sm">
                        {config.authentication_agreement}
                    </p>
                ))}
            <CardContent>
                {config.agreement_video_status !== "rejected" ? (
                    config.agreement_video_status === "accepted" ? (
                        <div className="border border-green-500/40 bg-green-500/5 w-full h-44 flex flex-col items-center justify-center gap-2 px-4 text-center">
                            <CircleCheck className="size-10 text-green-500" />
                            <p className="text-sm font-medium text-green-600 dark:text-green-400">
                                {t('video_verified')}
                            </p>
                        </div>
                    ) : (
                        <div className="border border-yellow-500/40 bg-yellow-500/5 w-full h-44 flex flex-col items-center justify-center gap-2 px-4 text-center">
                            <CircleAlert className="size-10 text-yellow-500" />
                            <p className="text-sm font-medium text-yellow-600 dark:text-yellow-400">
                                {t('under_review')}
                            </p>
                        </div>
                    )
                ) : (
                    <RecorderVideo setVideo={setVideo} />
                )}
            </CardContent>
        </Card>
    );
};
