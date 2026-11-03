'use client';

import {
    SetStateAction,
    startTransition,
    Suspense,
    useActionState,
    useEffect,
    useState,
} from 'react';
import { AlertCircle, CircleCheck } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AuthenticationSettings, StatusAuth } from '@/types/auth';
import { AlertError } from '@/hooks/alert-error';
import { userAuthentication } from '@/services/auth.services';
import { Alert, AlertIcon } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinners';
import { UploadFile } from './upload-file';
import { UploadVideo } from './upload-video';

export default function VideoVerify({
    ip,
    config,
    setStep,
    status,
    setStatus,
    description,
}: {
    ip: string;
    config: AuthenticationSettings;
    setStep: React.Dispatch<SetStateAction<number>>;
    description: string | null;
    status: StatusAuth;
    setStatus: React.Dispatch<SetStateAction<StatusAuth>>;
}) {
    const t = useTranslations('authentication');
    const verifiedNationalPicture = Boolean(
        config.id_card_image_status === "accepted" || config.id_card_image_status === "waiting"
    );
    const verifiedVideo = Boolean(
        config.agreement_video_status === "accepted" || config.agreement_video_status === "waiting"
    );
    const [file, setFile] = useState<File | null>(null);
    const [video, setVideo] = useState(null);
    const [validation, setValidation] = useState('');
    const [state, action, pending] = useActionState(userAuthentication, null);

    useEffect(() => {
        if (state?.message) {
            toast.success(state.message);
            setStep(2);
            setStatus(StatusAuth.WAITING);
        }
    }, [state, setStep]);

    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const formData = new FormData();

        if (!file && !verifiedNationalPicture) {
            setValidation(t('validation.image_id_card'));
            return;
        }
        if (!video && !verifiedVideo) {
            setValidation(t('validation.video'));
            return;
        }
        formData.append('ip_address', ip);
        if (file) {
            formData.append('id_card_image', file);
        }
        if (video?.[0]) {
            const clip = video[0] as Blob;
            const videoFile =
                clip instanceof File
                    ? clip
                    : new File([clip], `agreement-${Date.now()}.webm`, {
                          type: clip.type || 'video/webm',
                      });
            formData.append('agreement_video', videoFile, videoFile.name);
        }

        startTransition(() => action(formData));
    };

    return (
        <Card className="w-full mt-5">
            <CardContent className="p-6">
                {/*Alert Erorrs*/}
                {state && 'error' in state && state.error && (
                    <AlertError errors={state.error} />
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

                    {
                        status === StatusAuth.WAITING && (
                            <Alert variant="warning" className="mt-4 mb-12" appearance="light">
                                <AlertIcon></AlertIcon>
                                {t('awaiting_auth')}
                            </Alert>
                        )
                    }
                    {/*Description Erorr*/}
                    {description && (
                        <Alert variant="destructive" className='mb-12' appearance="light">
                            <AlertIcon>
                                <AlertCircle />
                            </AlertIcon>
                            {description}
                        </Alert>
                    )}


                    <form onSubmit={handleSubmit} className="block w-full space-y-5">
                        <div className="flex flex-col md:flex-row gap-y-3 items-start justify-around">
                            <UploadFile
                                setFile={setFile}
                                config={config}
                                verifiedNationalPicture={verifiedNationalPicture}
                            />
                            <UploadVideo
                                setVideo={setVideo}
                                config={config}
                                verifiedVideo={verifiedVideo}
                            />
                        </div>

                        {validation && (
                            <Alert variant="destructive" appearance="light">
                                <span className="text-destructive">{validation}</span>
                            </Alert>
                        )}

                        <CardFooter className="flex justify-end px-0 items-center">
                            {status === StatusAuth.WAITING ? (
                                <Button type="button" variant="secondary">
                                    {t('under_review')}
                                </Button>
                            ) : (
                                <Button type="submit" disabled={pending}>
                                    {pending ? (
                                        <Spinner className="size-4 animate-spin" />
                                    ) : null}
                                    {t('confirm')}
                                </Button>
                            )}
                        </CardFooter>
                    </form>
                </Suspense>
            </CardContent>
        </Card>
    );
}
