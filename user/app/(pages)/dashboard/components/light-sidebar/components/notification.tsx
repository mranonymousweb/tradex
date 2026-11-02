'use client';

import { useQuery } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { Notification, NotificationsType } from '@/types/notification';
import { getNotifications } from '@/services/notification.services';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { DataLoading } from '@/components/ui/data-loading';
import { NoData } from '@/components/ui/no-data';
import { DialogNotif } from './dialog-notif';

const Notifications = () => {
  const t = useTranslations('dashboard');
  const { data, isPending } = useQuery<NotificationsType>({
    queryKey: ['notifications'],
    queryFn: () => getNotifications(1, '&section=normal'),
  });
  const unreadNotif = data?.data?.filter(
    (notif: Notification) => notif.read === false,
  );

  return (
    <Card className="h-full">
      <CardHeader>
        <CardTitle>{t('title_notif')}</CardTitle>
        <i className="ki-outline ki-notification-on text-xl"></i>
      </CardHeader>

      <CardContent className="grow p-0">
        {isPending ? (
          <DataLoading />
        ) : unreadNotif?.length === 0 ? (
          <div className="flex items-center justify-center h-full py-10 lg:py-0">
            <NoData />
          </div>
        ) : (
          <ul className="flex flex-col h-[14rem] max-h-[25rem] overflow-y-auto">
            {unreadNotif?.map((item: Notification) => (
              <li
                key={item.id}
                className="py-2 px-3 hover:bg-accent/50 flex gap-2 w-full border-b last-of-type:border-0"
              >
                <div className="flex items-center justify-center bg-primary/10 rounded-full w-10 h-10">
                  <i className="ki-filled ki-notification text-xl"></i>
                </div>
                <div className="flex-1 flex items-center justify-between gap-8">
                  <div className="flex flex-col gap-1">
                    <div className="text-sm font-medium mb-px">
                      <span className="text-foreground block truncate w-44 line-clamp-2">
                        {item.title}
                      </span>
                    </div>
                    <span className="flex items-center text-xs font-medium text-muted-foreground">
                      {item.created_at}
                    </span>
                  </div>
                  <div className="flex gap-2">
                    <DialogNotif notification={item} />
                  </div>
                </div>
              </li>
            ))}
          </ul>
        )}
      </CardContent>
    </Card>
  );
};

export { Notifications };
