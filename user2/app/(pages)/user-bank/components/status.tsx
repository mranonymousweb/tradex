import { useTranslations } from 'next-intl';
import { Badge, BadgeDot } from '@/components/ui/badge';

type Status = 'active' | 'inactive' | 'waiting' | 'rejected';

interface StatusConfig {
  variant: 'success' | 'destructive' | 'warning';
  title: string;
}

export const StatusBnak = ({ info }: { info: Status }) => {
  const t = useTranslations('bank.table.status_option');

  const STATUS_CONFIG: Record<Status, StatusConfig> = {
    active: { variant: 'success', title: t('active') },
    inactive: { variant: 'destructive', title: t('not_active') },
    waiting: { variant: 'warning', title: t('waiting') },
    rejected: { variant: 'destructive', title: t('reject') },
  };
  const { variant, title } = STATUS_CONFIG[info];

  return (
    <Badge
      variant={variant}
      size="lg"
      shape="circle"
      appearance="outline"
      className="text-nowrap"
    >
      <BadgeDot />
      {title}
    </Badge>
  );
};
