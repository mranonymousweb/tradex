'use client';

import { Notification } from '@/types/notification';
import { UpdateActions } from '@/app/(pages)/notifications/components/update-action';

interface Item5Props {
  notification: Notification;
}

export default function Item({ notification }: Item5Props) {
  return (
    <li className="p-4 bg-accent/50 flex gap-2">
      <div className="flex items-center justify-center bg-primary/10 rounded-full w-10 h-10">
        <i className="ki-filled ki-notification text-xl"></i>
      </div>
      <div className="flex-1 flex items-center justify-between gap-8">
        <div className="flex flex-col gap-1">
          <div className="text-sm font-medium mb-px">
            <span className="text-mono font-semibold line-clamp-2">
              {notification.title}
            </span>
          </div>
          <span className="flex items-center text-xs font-medium text-muted-foreground">
            {notification.created_at}
          </span>
        </div>
        <div className="flex gap-2">
          <UpdateActions notification={notification} />
        </div>
      </div>
    </li>
  );
}
