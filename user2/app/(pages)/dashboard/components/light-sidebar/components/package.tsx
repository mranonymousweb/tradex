import { decimalPipe } from '@/helper/decimalPipe';
import { CircleCheck, CircleX } from 'lucide-react';
import { getTranslations } from 'next-intl/server';
import { PackageType } from '@/types/profile';
import { packages } from '@/services/dashboard.services';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface IHighlightsRow {
  text: string;
  amount: number;
}
type IHighlightsRows = Array<IHighlightsRow>;

interface IHighlightsItem {
  icon: React.ReactNode;
  label: string;
}
type IHighlightsItems = Array<IHighlightsItem>;

interface IHighlightsProps {
  limit?: number;
}

const Package = async ({ limit }: IHighlightsProps) => {
  const t = await getTranslations('dashboard');
  const data = await packages();
  const packageInfo = data
    ? data.filter((p: PackageType) => p.is_user_package === true)
    : [];
  const currentPackage = packageInfo[packageInfo.length - 1];
  const iconCheck = <CircleCheck size={18} className="text-green" />;
  const iconCircleX = <CircleX size={18} className="text-destructive" />;

  const rows: IHighlightsRows = [
    {
      text: t('fee_ticker_coin'),
      amount: currentPackage?.currency_taker_fee ?? 0,
    },
    {
      text: t('fee_micker_coin'),
      amount: currentPackage?.currency_maker_fee ?? 0,
    },
    {
      text: t('fee_ticker_fiat'),
      amount: decimalPipe(currentPackage?.fiat_taker_fee, 2) ?? 0,
    },
    {
      text: t('fee_micker_fiat'),
      amount: decimalPipe(currentPackage?.fiat_maker_fee, 2) ?? 0,
    },
  ];

  const items: IHighlightsItems = [
    {
      icon: currentPackage?.deposit ? iconCheck : iconCircleX,
      label: t('deposit_option'),
    },
    {
      icon: currentPackage?.withdrawal ? iconCheck : iconCircleX,
      label: t('withdrawal_option'),
    },
  ];

  const renderRow = (row: IHighlightsRow, index: number) => {
    return (
      <div
        key={index}
        className="flex items-center justify-between flex-wrap gap-2"
      >
        <div className="flex items-center gap-1.5">
          <i className="ki-filled ki-dollar"></i>
          <span className="text-sm font-normal text-mono">{row.text}</span>
        </div>
        <div className="flex items-center text-sm font-medium text-foreground gap-6">
          <span className="flex items-center justify-end gap-1">
            {row.amount}%
          </span>
        </div>
      </div>
    );
  };

  const renderItem = (item: IHighlightsItem, index: number) => {
    return (
      <div key={index} className="flex items-center gap-1.5">
        {item.icon}
        <span className="text-sm font-normal text-foreground">
          {item.label}
        </span>
      </div>
    );
  };

  return (
    <Card className="h-full">
      <CardHeader>
        <CardTitle>{t('status_autherntication')}</CardTitle>
        <i className="ki-outline ki-security-user text-xl"></i>
      </CardHeader>
      <CardContent className="flex flex-col gap-4 p-5 lg:p-7.5 lg:pt-4">
        <div className="flex flex-col gap-0.5">
          <span className="text-sm font-normal text-secondary-foreground">
            {t('level_you')}
          </span>
          <div className="flex items-center gap-2.5">
            <span className="text-3xl font-semibold text-mono">
              {currentPackage?.id}
            </span>
            <Badge size="sm" variant="success" appearance="outline">
              {currentPackage?.name ?? '--'}
            </Badge>
          </div>
        </div>
        <div className="flex items-center gap-1 mb-1.5">
          {data?.map((list: PackageType) => (
            <div
              key={list.id}
              className={`${list.is_user_package ? 'bg-green' : 'bg-secondary'} h-2 w-full rounded-xs`}
            ></div>
          ))}
        </div>
        <div className="flex items-center flex-wrap gap-4 mb-1">
          {items.map((item, index) => {
            return renderItem(item, index);
          })}
        </div>
        <div className="border-b border-input"></div>
        <div className="grid gap-3">{rows.slice(0, limit).map(renderRow)}</div>
      </CardContent>
    </Card>
  );
};

export {
  Package,
  type IHighlightsRow,
  type IHighlightsRows,
  type IHighlightsItem,
  type IHighlightsItems,
  type IHighlightsProps,
};
