/* eslint-disable @typescript-eslint/no-explicit-any */
'use client';

import { useTranslations } from 'next-intl';
import { StatusConfigItem, StatusKey } from '@/types/swap';
import { Badge, BadgeDot } from '@/components/ui/badge';

export const StatusSwap = ({ info }: { info: any }) => {
  const t = useTranslations('swap.table.dialog_filter');
  const status = info.row.original.status as keyof typeof STATUS_CONFIG;

  const STATUS_CONFIG: Record<StatusKey, StatusConfigItem> = {
    accepted_by_site: { variant: 'success', title: t('successful') },
    accepted_by_supplier: { variant: 'success', title: t('successful') },
    waiting: { variant: 'warning', title: t('waiting') },
    waiting_by_supplier: { variant: 'warning', title: t('waiting') },
    rejected_by_site: { variant: 'destructive', title: t('unSuccessful') },
    rejected_by_supplier: { variant: 'warning', title: t('waiting') },
  };

  const { variant, title } = STATUS_CONFIG[status];

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
