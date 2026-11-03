'use client';

import { startTransition, useActionState, useState } from 'react';
import { CircleAlert } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { AlertError } from '@/hooks/alert-error';
import { updateFieldProfile } from '@/services/setting-account.services';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input, InputWrapper } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinners';
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip';
import { VerifyCode } from './verify-code';

const AuthEmail = () => {
  const t = useTranslations('profile');
  const [newEmail, setNewEmail] = useState('');
  const [code, setCode] = useState('');
  const [state, action, pending] = useActionState(updateFieldProfile, null);

  const handleClick = () => {
    const data = {
      code,
      field: newEmail,
      section: 'email',
    };
    startTransition(() => action(data));
  };

  return (
    <Card>
      <CardHeader id="auth_email">
        <CardTitle>{t('edit_email')}</CardTitle>
      </CardHeader>
      <AlertError errors={state?.error} />

      <CardContent className="grid gap-5">
        <div className="w-full">
          <div className="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
            <Label className="flex w-full max-w-56">{t('new_email')}</Label>
            <Input
              type="email"
              placeholder={t('placeholder_new_email')}
              defaultValue={newEmail}
              onChange={(e) => setNewEmail(e.target.value)}
            />
          </div>
        </div>
        <div className="w-full">
          <div className="flex items-baseline flex-wrap lg:flex-nowrap gap-1">
            <Label className="flex w-full gap-x-1 max-w-56">
              {t('confirm_code')}
              <TooltipProvider>
                <Tooltip delayDuration={50}>
                  <TooltipTrigger className="cursor-pointer">
                    <CircleAlert size={16} fill="none" color="#9a9c9f" />
                  </TooltipTrigger>
                  <TooltipContent className="text-xs p-2">
                    {t('tooltip_confirm_code')}
                  </TooltipContent>
                </Tooltip>
              </TooltipProvider>
            </Label>
            ‌
            <InputWrapper>
              <Input
                type="text"
                placeholder={t('placeholder_code')}
                defaultValue={code}
                onChange={(e) => setCode(e.target.value)}
              />

              <VerifyCode section="change_email" />
            </InputWrapper>
          </div>
        </div>
        <div className="flex justify-end pt-2.5">
          <Button type="button" disabled={pending} onClick={handleClick}>
            {t('save_changes')}
            {pending ? <Spinner className="size-4 animate-spin" /> : null}
          </Button>
        </div>
      </CardContent>
    </Card>
  );
};

export { AuthEmail };
