'use client';

import { startTransition, useActionState, useEffect, useState } from 'react';
import { CircleAlert } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { User } from '@/types/auth';
import { updateAvatar } from '@/services/setting-account.services';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinners';
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip';
import { ImageInputFile } from '@/components/image-input';
import { AvatarInput } from '@/components/partials/common/avatar-input';

const BasicSettings = ({ user }: { user: User }) => {
  const t = useTranslations('profile');
  const [file, setFile] = useState<ImageInputFile[] | null>(null);
  const [state, action, pending] = useActionState(updateAvatar, null);

  useEffect(() => {
    if (state?.success) {
      toast.success(state.message);
    } else if (state?.error) {
      toast.error(state.message);
    }
  }, [state]);

  const handleclick = () => {
    const formData = new FormData();
    const img = file ? file[0].file : undefined;
    if (img) {
      formData.append('avatar', img);
    }
    startTransition(() => action(formData));
  };

  return (
    <Card className="pb-2.5">
      <CardHeader id="basic_settings">
        <CardTitle>{t('info_account')}</CardTitle>
      </CardHeader>
      <CardContent className="grid gap-5">
        <div className="flex items-center flex-wrap gap-2.5">
          <Label className="flex w-full max-w-56">{t('picture')}</Label>
          <div className="flex items-center justify-between flex-wrap grow gap-2.5">
            <span className="text-sm text-secondary-foreground">
              150x150px JPEG, PNG Image
            </span>
            <AvatarInput img={user?.avatar} setFile={setFile} />
          </div>
        </div>
        <div className="w-full">
          <div className="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
            <Label className="flex w-full items-center gap-1 max-w-56">
              {t('name')}
              <TooltipProvider>
                <Tooltip delayDuration={50}>
                  <TooltipTrigger className="cursor-pointer">
                    <CircleAlert size={16} fill="none" color="#9a9c9f" />
                  </TooltipTrigger>
                  <TooltipContent className="text-xs p-2">
                    {t('impossible_edit')}
                  </TooltipContent>
                </Tooltip>
              </TooltipProvider>
            </Label>
            <Input type="text" defaultValue={user?.name} readOnly />
          </div>
        </div>
        <div className="w-full">
          <div className="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
            <Label className="flex w-full items-center gap-1 max-w-56">
              {t('family')}
              <TooltipProvider>
                <Tooltip delayDuration={50}>
                  <TooltipTrigger className="cursor-pointer">
                    <CircleAlert size={16} fill="none" color="#9a9c9f" />
                  </TooltipTrigger>
                  <TooltipContent className="text-xs p-2">
                    {t('impossible_edit')}
                  </TooltipContent>
                </Tooltip>
              </TooltipProvider>
            </Label>
            <Input type="text" defaultValue={user?.family} readOnly />
          </div>
        </div>
        <div className="w-full">
          <div className="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
            <Label className="flex w-full items-center gap-1 max-w-56">
              {t('email')}
            </Label>
            <Input type="text" defaultValue={user?.email} readOnly />
          </div>
        </div>
        <div className="w-full">
          <div className="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
            <Label className="flex w-full items-center gap-1 max-w-56">
              {t('phone')}
              <TooltipProvider>
                <Tooltip delayDuration={50}>
                  <TooltipTrigger className="cursor-pointer">
                    <CircleAlert size={16} fill="none" color="#9a9c9f" />
                  </TooltipTrigger>
                  <TooltipContent className="text-xs p-2">
                    {t('impossible_edit')}
                  </TooltipContent>
                </Tooltip>
              </TooltipProvider>
            </Label>
            <Input
              type="text"
              placeholder="Enter phone"
              defaultValue={user?.mobile}
              readOnly
            />
          </div>
        </div>
        <div className="flex justify-end pt-2.5">
          <Button type="button" disabled={pending} onClick={handleclick}>
            {pending ? <Spinner className="size-4 animate-spin" /> : null}
            {t('save_changes')}
          </Button>
        </div>
      </CardContent>
    </Card>
  );
};

export { BasicSettings };
