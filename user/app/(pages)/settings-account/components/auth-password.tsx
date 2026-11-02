'use client';

import { startTransition, useActionState, useState } from 'react';
import { CircleAlert, Eye, EyeOff } from 'lucide-react';
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

const AuthPassword = () => {
  const t = useTranslations('profile');
  const [passwordVisible, setPasswordVisible] = useState(false);
  const [newPassword, setNewPassword] = useState('');
  const [code, setCode] = useState('');
  const [state, action, pending] = useActionState(updateFieldProfile, null);

  const handleClick = () => {
    if (!newPassword && !code) {
      return;
    }
    const data = {
      code,
      field: newPassword,
      section: 'password',
    };
    startTransition(() => action(data));
  };

  return (
    <Card>
      <CardHeader id="auth_password">
        <CardTitle>{t('edit_password')}</CardTitle>
      </CardHeader>
      <AlertError errors={state?.error} />

      <CardContent className="grid gap-5">
        <div className="w-full">
          <div className="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
            <Label className="flex w-full max-w-56">{t('new_password')}</Label>
            <div className="relative w-full">
              <Input
                type={passwordVisible ? 'text' : 'password'} // Toggle input type
                placeholder={t('placeholder_password')}
                defaultValue={newPassword}
                onChange={(e) => setNewPassword(e.target.value)}
              />
              <Button
                type="button"
                variant="ghost"
                mode="icon"
                size="sm"
                onClick={() => setPasswordVisible(!passwordVisible)} // Toggle visibility
                className="absolute end-0 top-1/2 -translate-y-1/2 h-7 w-7 me-1.5 bg-transparent!"
                aria-label={passwordVisible ? 'Hide password' : 'Show password'}
              >
                {!passwordVisible ? (
                  <EyeOff className="text-muted-foreground" />
                ) : (
                  <Eye className="text-muted-foreground" />
                )}
              </Button>
            </div>
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
              <VerifyCode section="change_password" />
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

export { AuthPassword };
