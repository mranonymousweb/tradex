import { useState } from 'react';
import Image from 'next/image';
import { CircleAlert, CircleCheck, ImageUp } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { AuthenticationSettings } from '@/types/auth';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { DialogImageGuide } from './dialog-image';

export const UploadFile = ({
    setFile,
    config,
    verifiedNationalPicture = false,
}: {
    setFile: React.Dispatch<File | null>;
    config: AuthenticationSettings;
    verifiedNationalPicture?: boolean;
}) => {
    const t = useTranslations('authentication');
    const [img, setImg] = useState('');
    const handleFile = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (verifiedNationalPicture) return;
        const file = e.target.files?.[0];
        if (file) {
            setImg(URL.createObjectURL(file));
            setFile(file);
        }
    };

    return (
        <Card className="w-80">
            <CardHeader className="py-1">
                <CardTitle className="text-sm">
                    {t('upolod_picture_national')}
                </CardTitle>
                {!verifiedNationalPicture && <DialogImageGuide config={config} />}
            </CardHeader>
            <CardContent>
                {config.id_card_image_status !== "rejected" ? (
                    config.id_card_image_status === "accepted" ? (
                        <div className="border border-green-500/40 bg-green-500/5 w-full h-44 flex flex-col items-center justify-center gap-2 px-4 text-center">
                            <CircleCheck className="size-10 text-green-500" />
                            <p className="text-sm font-medium text-green-600 dark:text-green-400">
                                {t('national_picture_verified')}
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
                    <div className="flex flex-col">
                        <div className="border border-mono border-dashed w-full h-44 bg-secondary/50 flex flex-col items-center justify-center">
                            <ImageUp size={40} color="#bdbfc1" />
                            {img && (
                                <Image
                                    src={img}
                                    width={176}
                                    height={176}
                                    className="h-44 w-full"
                                    alt="image"
                                />
                            )}
                        </div>

                        <Button asChild variant="primary" size="sm" className="mt-3 w-full">
                            <label htmlFor="inputFile">
                                {t('upload_file')}
                                <input
                                    type="file"
                                    accept="png"
                                    id="inputFile"
                                    onChange={(e) => handleFile(e)}
                                    className="hidden"
                                />
                            </label>
                        </Button>
                    </div>
                )}
            </CardContent>
        </Card>
    );
};
