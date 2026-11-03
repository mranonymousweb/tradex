import { useEffect, useState } from 'react';
import { Check, ChevronDown } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { UseFormReturn } from 'react-hook-form';
import { cn } from '@/lib/utils';
import { FormType } from './constants';
import { FormSchemaType } from './schema';

const STOP_TABS = [FormType.StopLimit, FormType.StopMarket] as const;

type StopTabType = (typeof STOP_TABS)[number];

interface Props {
  form: UseFormReturn<FormSchemaType>;
}

const tabClass = (isActive: boolean) =>
  cn('pb-2 cursor-pointer hover:text-mono', isActive && 'active_tab');

export const Tabs = ({ form }: Props) => {
  const t = useTranslations('spot');
  const type = Number(form.watch('type'));
  const [dropDown, setDropDown] = useState<StopTabType>(FormType.StopLimit);

  const isStopTab = STOP_TABS.includes(type as StopTabType);

  useEffect(() => {
    if (isStopTab) {
      setDropDown(type as StopTabType);
    }
  }, [type, isStopTab]);

  const setType = (value: FormType) => {
    form.setValue('type', value, { shouldDirty: true });
  };

  const selectStopTab = (value: StopTabType) => {
    setDropDown(value);
    setType(value);
  };

  const stopOptions = [
    { value: FormType.StopLimit, label: t('stop_limit') },
    { value: FormType.StopMarket, label: t('stop_market') },
  ] as const;

  return (
    <div className="flex items-center gap-x-2 text-sm font-medium text-muted-foreground border-b w-full">
      <div
        className={tabClass(type === FormType.Limit)}
        onClick={() => setType(FormType.Limit)}
      >
        <span>{t('limit')}</span>
      </div>
      <div
        className={tabClass(type === FormType.Market)}
        onClick={() => setType(FormType.Market)}
      >
        <span>{t('market_tab')}</span>
      </div>
      <div className="tab-drop relative cursor-pointer">
        <div
          className={cn(
            'tab-drop--selected flex items-center pb-2 space-x-2',
            isStopTab && 'active_tab',
          )}
          onClick={() => setType(dropDown)}
        >
          <span>
            {dropDown === FormType.StopLimit
              ? t('stop_limit')
              : t('stop_market')}
          </span>
          <ChevronDown className="w-4 h-4" />
        </div>

        <div className="tab-drop--list flex flex-col left-0 top-7 z-[1] absolute bg-background border shadow-lg rounded-lg w-32">
          {stopOptions.map(({ value, label }) => (
            <div
              key={value}
              className={cn(
                'flex justify-between items-center p-2 hover:bg-secondary cursor-pointer',
                dropDown === value && 'bg-secondary',
              )}
              onClick={() => selectStopTab(value)}
            >
              <span className="text-secondary-foreground">{label}</span>
              {dropDown === value && <Check size={15} className="mb-1" />}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};
