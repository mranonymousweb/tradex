import { useState } from 'react';
import Image from 'next/image';
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

export const DialogImageGuide = ({
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
          <DialogDescription>{t('guide_upload_text')}</DialogDescription>
          {!config ? (
            <div className="flex items-center justify-center h-48">
              <Spinner />
            </div>
          ) : (
            <div className="flex flex-col items-center">
              <div className="bg-secondary w-48 h-48 rounded-lg mt-5">
                {config?.authentication_identity_image && (
                  <Image
                    src={config?.authentication_identity_image}
                    width={192}
                    height={192}
                    className="w-48 h-48 object-cover"
                    alt="not-found"
                  />
                )}
              </div>
              <span className="text-xs text-muted-foreground mt-1">
                192*192 (PNG, JPG, SVG)
              </span>
              <p className="text-justify text-secondary-foreground text-sm mt-4 border border-dashed p-2">
                {config?.authentication_image_guide}
              </p>
            </div>
          )}
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
