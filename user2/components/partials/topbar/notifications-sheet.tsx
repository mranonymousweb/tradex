'use client';

import { ReactNode } from 'react';
import Link from 'next/link';
import { useTranslations } from 'next-intl';
import { Notification } from '@/types/notification';
import { Button } from '@/components/ui/button';
import { NoData } from '@/components/ui/no-data';
import {
  Sheet,
  SheetBody,
  SheetClose,
  SheetContent,
  SheetFooter,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet';
import { ContentLoader } from '@/components/common/content-loader';
import { UpdateActions } from '@/app/(pages)/notifications/components/update-action';
import Item from './notifications/item';

export function NotificationsSheet({
  isPending,
  unReadNotifications,
  trigger,
}: {
  isPending: boolean;
  unReadNotifications?: Notification[];
  trigger: ReactNode;
}) {
  const t = useTranslations('notification');

  return (
    <>
      <Sheet>
        <SheetTrigger asChild>{trigger}</SheetTrigger>
        <SheetContent className="gap-0 w-full sm:w-[500px] sm:inset-5 sm:start-auto h-auto rounded-lg p-0 sm:max-w-none [&_[data-slot=sheet-close]]:top-4.5 [&_[data-slot=sheet-close]]:end-5">
          <SheetHeader className="px-3 py-4 border-b border-border">
            <SheetTitle>{t('notification')}</SheetTitle>
          </SheetHeader>

          <SheetBody className="kt-scrollable-y-auto grow space-y-1">
            {isPending ? (
              <ContentLoader />
            ) : unReadNotifications?.length === 0 ? (
              <div className="flex items-center justify-center h-full">
                <NoData />
              </div>
            ) : (
              <ul>
                {unReadNotifications &&
                  unReadNotifications.map((notification) => (
                    <Item key={notification.id} notification={notification} />
                  ))}
              </ul>
            )}
          </SheetBody>
          <SheetFooter className="border-t border-border p-5 grid grid-cols-2 gap-2.5">
            <SheetClose asChild>
              <Button variant="outline" asChild>
                <Link href="/notifications">{t('show_all')}</Link>
              </Button>
            </SheetClose>
            <UpdateActions isUpdatingAll={true} />
          </SheetFooter>
        </SheetContent>
      </Sheet>
    </>
  );
}
