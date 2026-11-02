'use client';

import { Suspense } from 'react';
import { CircleCheck } from 'lucide-react';
import 'react-phone-input-2/lib/style.css';
import Image from 'next/image';
import { useTranslations } from 'next-intl';
import { StatusAuth } from '@/types/auth';
import { Card, CardContent, CardHeader } from '@/components/ui/card';

export const Approval = ({ status }: { status: StatusAuth }) => {
  const t = useTranslations('authentication');
  return (
    <Card className="w-full mt-5">
      <CardContent className="p-6">
        <Suspense>
          <CardHeader>
            <div className="flex flex-col md:flex-row gap-2 pb-3 md:pb-0">
              <div className="flex items-center gap-x-2 text-primary">
                <CircleCheck color="#1379f0" />
                <span>{t('header_option_1')}</span>
                <span className="hidden md:inline-block h-px w-32 bg-primary"></span>
              </div>
              <div className="flex items-center gap-x-2 text-primary">
                <CircleCheck color="#1379f0" />
                <span>{t('send_document')}</span>
                <span className="hidden md:inline-block h-px w-32 bg-primary"></span>
              </div>
              <div className="flex items-center gap-x-2 text-primary">
                <CircleCheck color="#1379f0" />
                <span>{t('header_option_4')}</span>
              </div>
            </div>
          </CardHeader>
          <div className="flex flex-col items-center mt-6">
            {(status === StatusAuth.WAITING ||
              status === StatusAuth.REJECTED) && (
              <>
                <div className="flex flex-col items-center">
                  <Image
                    src="/media/ui/clockwise.png"
                    width={40}
                    height={40}
                    alt="verify"
                  />
                  <span className="text-yellow-500 mt-3 text-base font-semibold">
                    {t('awaiting')}
                  </span>
                </div>
                <p className="text-sm text-secondary-foreground mt-4">
                  {t('awaiting_auth')}
                </p>
              </>
            )}

            {status === StatusAuth.ACCEPTED && (
              <div className="flex flex-col justify-center items-center">
                <Image
                  src="/media/ui/verify.png"
                  width={45}
                  height={45}
                  alt="verify"
                />
                <span className="text-green mt-3 text-base font-semibold text-center">
                  {t('confirm_auth')}
                </span>
              </div>
            )}
          </div>
        </Suspense>
      </CardContent>
    </Card>
  );
};
