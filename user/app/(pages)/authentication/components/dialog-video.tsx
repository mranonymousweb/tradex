import { useState } from 'react';
import { useTranslations } from 'next-intl';
import { AuthenticationSettings } from '@/types/auth';
import { Button } from '@/components/ui/button';
import DialogContent, {
  Dialog,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinners';

export const DialogVideoGuide = ({
  config,
}: {
  config: AuthenticationSettings;
}) => {
  const t = useTranslations('authentication');
  const [open, setOpen] = useState(false);
  const closeDialog = () => setOpen(false);

  return (
    <>
      <Button
        variant="outline"
        className="flex items-center"
        onClick={() => setOpen(true)}
      >
        <i className="ki-outline ki-question-2"></i>
        <span>{t('guide')}</span>
      </Button>

      <Dialog open={open} onOpenChange={closeDialog}>
        <DialogContent className="w-[450px] max-h-[80vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{t('guide')}</DialogTitle>
          </DialogHeader>
          <DialogDescription>{t('guide_record_video')}</DialogDescription>
          {/*<DialogContent>*/}
          <div className="flex flex-col items-center mt-4">
            <p className="text-justify text-secondary-foreground text-sm mt-4 border border-dashed p-2">
              {!config ? <Spinner /> : config.authentication_video_guide}
            </p>
          </div>
          {/*</DialogContent>*/}
          <DialogFooter>
            <Button type="button" variant="primary" onClick={closeDialog}>
              {t('close')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
};
