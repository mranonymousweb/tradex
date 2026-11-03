import { startTransition, useActionState, useEffect, useMemo, useState } from 'react';
import { useQueryClient } from '@tanstack/react-query';
import DOMPurify from 'dompurify';
import { useTranslations } from 'next-intl';
import { Notification } from '@/types/notification';
import { updateNotification } from '@/services/notification.services';
import {
  AlertDialog,
  AlertDialogContent,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';

export const PopupNotif = ({ notif }: { notif: Notification }) => {
  const t = useTranslations('notification');
  const [popupAlert, setPopupAlert] = useState(!notif?.read);
  const [state, action, pending] = useActionState(updateNotification, null);
  const queryClient = useQueryClient();
  const sanitizedDescription = useMemo(
    () => DOMPurify.sanitize(notif?.description ?? ''),
    [notif?.description],
  );

  useEffect(() => {
    if (state?.success) {
      queryClient.invalidateQueries({ queryKey: ['notifications'] });
      setPopupAlert(false);
    }
  }, [state, queryClient]);

  const handleReadNotif = () => {
    const formData = {
      id: notif?.id,
    };
    startTransition(() => action(formData));
  };

  return (
    <AlertDialog open={popupAlert} onOpenChange={setPopupAlert}>
      <AlertDialogContent className="px-0">
        <AlertDialogTitle className="px-5">{notif?.title}</AlertDialogTitle>
        <div className="max-h-[80vh] overflow-y-auto px-5">
          <div dangerouslySetInnerHTML={{ __html: sanitizedDescription }} />
        </div>
        <div className="flex items-center gap-x-2 justify-end px-5">
          <Button
            variant="primary"
            onClick={handleReadNotif}
            disabled={pending}
          >
            {pending ? <Spinner className="animate-spin" /> : null}
            {t('accept_alert')}
          </Button>
        </div>
      </AlertDialogContent>
    </AlertDialog>
  );
};
