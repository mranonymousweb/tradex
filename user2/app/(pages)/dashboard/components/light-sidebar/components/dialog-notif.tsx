'use client';

import { startTransition, useActionState, useEffect, useState } from 'react';
import { useQueryClient } from '@tanstack/react-query';
import { ArrowLeft } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { Notification } from '@/types/notification';
import { updateNotification } from '@/services/notification.services';
import { Button } from '@/components/ui/button';
import { NotificationDialog } from '@/app/(pages)/notifications/components/notification-dialog';

interface Props {
  isUpdatingAll?: boolean;
  notification?: Notification;
}

export const DialogNotif: React.FC<Props> = ({ notification }) => {
  const t = useTranslations('dashboard');
  const [openDialog, setOpenDialog] = useState(false);
  const [state, action, pending] = useActionState(updateNotification, null);
  const queryClient = useQueryClient();

  useEffect(() => {
    if (state?.success) {
      queryClient.invalidateQueries({ queryKey: ['notifications'] });
      toast.success(t('success'));
      setOpenDialog(false);
    }
  }, [state, queryClient, t]);

  const handleSubmit = () => {
    if (notification?.read) return;
    const data = new FormData();
    if (notification?.id) {
      data.append('id', notification.id);
    }
    startTransition(() => action(data));
  };

  return (
    <>
      <Button
        type="button"
        size="sm"
        variant="secondary"
        onClick={() => setOpenDialog(true)}
      >
        <ArrowLeft
          size={15}
          className="text-mono rtl:rotate-0 ltr:rotate-180"
        />
      </Button>
      <NotificationDialog
        open={openDialog}
        pending={pending}
        title={notification?.title}
        description={notification?.description}
        handleSubmit={handleSubmit}
        setOpenDialog={setOpenDialog}
        isRead={notification?.read ?? true}
      />
    </>
  );
};
