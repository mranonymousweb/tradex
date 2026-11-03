'use client';

import { useActionState, useEffect, useState } from 'react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { sendOtp } from '@/services/setting-account.services';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';

export const VerifyCode = ({ section }: { section: string }) => {
  const t = useTranslations('profile');
  const [seconds, setSeconds] = useState(0);
  const [state, action, pending] = useActionState(sendOtp, null);

  useEffect(() => {
    if (state?.success) {
      setSeconds(120);
      toast.success(t('success_text'));
    }
  }, [state, t]);

  useEffect(() => {
    const intervalId = setInterval(() => {
      setSeconds((s) => s - 1);
    }, 1000);

    return () => clearInterval(intervalId);
  }, [seconds]);

  const minutes = Math.floor(seconds / 60);
  const second = seconds % 60;

  return (
    <form action={action}>
      <input type="hidden" name="section" defaultValue={section} />
      <Button
        type="submit"
        variant="dim"
        className="font-sans text-primary disabled:opacity-100"
        disabled={pending || seconds > 0}
      >
        {pending ? <Spinner className="size-4 animate-spin" /> : null}
        {seconds > 0
          ? `${minutes}:${second.toString().padStart(2, '0')}`
          : t('send_code')}
      </Button>
    </form>
  );
};
