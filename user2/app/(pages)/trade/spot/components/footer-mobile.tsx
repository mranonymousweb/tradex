import { useTranslations } from 'next-intl';
import { Button } from '@/components/ui/button';

interface Props {
  buttomSheet: boolean;
  setButtomSheet: (val: boolean) => void;
  setSwitchTab: (tab: boolean) => void;
}
export const FooterMobile = ({
  buttomSheet,
  setButtomSheet,
  setSwitchTab,
}: Props) => {
  const t = useTranslations('spot');

  const handleSwitchTab = (value: boolean) => {
    setSwitchTab(value);
    setButtomSheet(true);
  };

  return (
    <>
      <div className="fixed bottom-0 w-full space-x-3 z-10 border bg-background p-5 flex justify-between md:hidden">
        <Button
          onClick={() => handleSwitchTab(true)}
          className="bg-destructive/70 text-background w-full hover:opacity-80 hover:bg-destructive/70 dark:text-white"
        >
          {t('sell')}
        </Button>
        <Button
          onClick={() => handleSwitchTab(false)}
          className="bg-green text-background w-full hover:opacity-80 hover:bg-green"
        >
          {t('buy')}
        </Button>
      </div>
      {buttomSheet && (
        <div
          className="overlay-market"
          onClick={() => setButtomSheet(false)}
        ></div>
      )}
    </>
  );
};
