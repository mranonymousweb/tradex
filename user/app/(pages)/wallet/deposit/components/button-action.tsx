import { useTranslations } from 'next-intl';
import { PackageType } from '@/types/profile';
import { Supplier, WalletRecive } from '@/types/wallet';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';

export const ButtonAction = ({
  address,
  autoCard,
  packageInfo,
  pending,
}: {
  address: WalletRecive | null;
  autoCard: boolean;
  packageInfo: PackageType;
  pending: boolean;
}) => {
  const t = useTranslations('wallet.deposit');

  if (address?.supplier === Supplier.NOTSUPPLIER) return <></>;
  if (autoCard) return <></>;

  return (
    <div className="flex justify-end">
      <Button
        disabled={!packageInfo?.deposit || pending}
        variant={packageInfo?.deposit ? 'primary' : 'secondary'}
      >
        {pending ? <Spinner className="size-4 animate-spin" /> : null}
        {t('confirm')}
      </Button>
    </div>
  );
};
