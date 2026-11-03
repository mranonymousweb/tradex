import { startTransition, useActionState, useEffect, useState } from 'react';
import { useQueryClient } from '@tanstack/react-query';
import { ArrowLeft } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { Notification } from '@/types/notification';
import { updateNotification } from '@/services/notification.services';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';
import { NotificationDialog } from './notification-dialog';

interface Props {
  isUpdatingAll?: boolean;
  notification?: Notification;
}

export const UpdateActions: React.FC<Props> = ({
  notification,
  isUpdatingAll = false,
}) => {
  const t = useTranslations('notification.table');
  const queryClient = useQueryClient();
  const [openDialog, setOpenDialog] = useState(false);
  const [state, action, pending] = useActionState(updateNotification, null);

  useEffect(() => {
    if (state?.success) {
      queryClient.invalidateQueries({ queryKey: ['notifications'] });
      toast.success(t('success'));
      setOpenDialog(false);
    }
  }, [state, isUpdatingAll, queryClient, t]);

  const handleSubmit = () => {
    if (!isUpdatingAll && notification?.read) return;
    const data = new FormData();
    if (notification?.id) {
      data.append('id', notification.id);
    }
    startTransition(() => action(data));
  };

  return (
    <>
      {!isUpdatingAll ? (
        <Button
          type="button"
          size="sm"
          variant="secondary"
          onClick={() => setOpenDialog(true)}
        >
          <ArrowLeft size={15} className="rtl:rotate-0 ltr:rotate-180" />
        </Button>
      ) : (
        <Button variant="outline" onClick={handleSubmit}>
          {pending ? <Spinner className="animate-spin" /> : null}
          {t('all_read')}
        </Button>
      )}
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
