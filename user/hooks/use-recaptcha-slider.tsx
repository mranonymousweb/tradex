'use client';

import { useEffect, useRef } from 'react';
import '../lib/slidercaptcha';
import '@/css/components/captcha.css';
import { useTranslations } from 'next-intl';

declare global {
  interface Window {
    sliderCaptcha: any;
  }
}

interface Props {
  setShowRecaptchaPuzzle: (p: boolean) => void;
  onSubmit: () => void;
}

export default function ValidateRecaptchaSlider({
  setShowRecaptchaPuzzle,
  onSubmit,
}: Props) {
  const t = useTranslations('auth');
  const ref = useRef<HTMLDivElement | null>(null);
  const captcha = useRef<any>(null);

  useEffect(() => {
    if (ref.current && !captcha.current && typeof window !== 'undefined') {
      captcha.current = window.sliderCaptcha({
        element: ref.current,
        loadingText: t('loading_captcha'),
        failedText: t('try_again'),
        barText: t('slide_captcha'),
        repeatIcon: 'ki-outline ki-arrows-circle',
        onSuccess: function () {
          setTimeout(function () {
            setShowRecaptchaPuzzle(false);
            onSubmit();
            captcha.current.reset();
          }, 1000);
        },
      });
    }
  }, []);

  return (
    <div className="fixed w-full h-full bg-mono/30">
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-background h-60 p-5 rounded-lg">
        <div ref={ref}></div>
      </div>
    </div>
  );
}
