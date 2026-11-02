'use client';

import { startTransition, useActionState, useEffect, useState } from 'react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { getData, setData } from '@/lib/storage';
import { recentCode } from '@/services/auth.services';
import { Spinner } from '@/components/ui/spinners';

export const VerifyCode = () => {
  const t = useTranslations('wallet.withdrawal');
  const [seconds, setSeconds] = useState(0);
  const [state, action, pending] = useActionState(recentCode, null);

  useEffect(() => {
    if (state?.success) {
      setData('count', 120);
      setSeconds(120);
      const message = t('success_text');
      toast.success(message);
    }
  }, [state, t]);

  useEffect(() => {
    const timer = getData('count');
    if (Number(timer) <= 0) return;

    const intervalId = setInterval(() => {
      const newtime = Number(timer) - 1;
      setSeconds(newtime);
      setData('count', newtime);
    }, 1000);

    return () => clearInterval(intervalId);
  }, [seconds]);
  const minutes = Math.floor(seconds / 60);
  const second = seconds % 60;

  return (
    <button
      type="button"
      className="text-xs flex items-center gap-x-1 font-semibold text-primary hover:text-primary cursor-pointer"
      onClick={async () => {
        await startTransition(() => action());
      }}
      disabled={pending}
    >
      {pending ? <Spinner className="size-4 animate-spin" /> : null}
      {seconds > 0
        ? `${minutes}:${second.toString().padStart(2, '0')}`
        : t('send_code')}
    </button>
  );
};
