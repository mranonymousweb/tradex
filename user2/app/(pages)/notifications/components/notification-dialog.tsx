import { useTranslations } from 'next-intl';
import { Button } from '@/components/ui/button';
import DialogContent, {
  Dialog,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';

interface Props {
  open: boolean;
  title?: string;
  description?: string;
  pending: boolean;
  isRead: boolean;
  handleSubmit: () => void;
  setOpenDialog: (open: boolean) => void;
}
export const NotificationDialog: React.FC<Props> = ({
  open,
  title,
  description,
  pending,
  handleSubmit,
  isRead,
  setOpenDialog,
}) => {
  const t = useTranslations('notification.table');
  const handleClick = () => {
    if (isRead) {
      setOpenDialog(false);
      return;
    }
    handleSubmit();
  };

  return (
    <Dialog open={open}>
      <DialogContent close={false} className="w-[300px] md:w-[600px]">
        <DialogHeader>
          <DialogTitle>{t('details')}</DialogTitle>
        </DialogHeader>
        <div className="flex flex-col gap-5">
          <div className="flex flex-col gap-2">
            <label htmlFor="" className="font-semibold">
              {t('title')}
            </label>
            <div className="overflow-y-auto max-h-32 px-3 py-1 bg-secondary rounded-sm">
              <p className="text-sm rounded-lg">{title}</p>
            </div>
          </div>
          <div className="flex flex-col gap-2">
            <label htmlFor="" className="font-semibold">
              {t('description')}
            </label>
            <div className="overflow-y-auto max-h-62 px-3 py-1 bg-secondary rounded-sm">
              <p className="text-sm rounded-lg">{description}</p>
            </div>
          </div>
        </div>
        <DialogFooter className="flex gap-1 justify-center">
          <Button onClick={handleClick}>
            {pending ? <Spinner className="animate-spin" /> : null}
            {t('status_option.read')}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
};
