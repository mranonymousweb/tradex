import { useTranslations } from 'next-intl';
import { Input } from '@/components/ui/input';

interface Props {
  input: string;
  setInput: (input: string) => void;
}

export const VerifyAccountGoogle = ({ input, setInput }: Props) => {
  const t = useTranslations('auth');

  return (
    <>
      <div className="text-center mb-2">
        <h3 className="text-lg font-semibold text-foreground mb-5">
          {t('confirm_account')}
        </h3>
        <div className="flex flex-col">
          <span className="text-2sm text-muted-foreground font-medium mb-1.5">
            {t('totp_code')}
          </span>
          <span className="text-sm text-primary">*Google Authentication*</span>
        </div>
      </div>
      <div className="flex flex-wrap justify-center gap-2.5 relative">
        <Input
          type="number"
          maxLength={6}
          value={input}
          onChange={(e) => setInput(e.target.value)}
          placeholder={t('placeholder_code')}
        />
      </div>
    </>
  );
};
