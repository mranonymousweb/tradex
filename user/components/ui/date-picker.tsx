'use client';

import { useState } from 'react';
import { cva } from 'class-variance-authority';
import { useLocale, useTranslations } from 'next-intl';
import gregorian from 'react-date-object/calendars/gregorian';
import persian from 'react-date-object/calendars/persian';
import gregorian_en from 'react-date-object/locales/gregorian_en';
import persian_fa from 'react-date-object/locales/persian_fa';
import DatePicker, { DateObject } from 'react-multi-date-picker';
import { cn } from '@/lib/utils';

const inputVariants = cva(
  `
    flex w-full bg-input border border-border shadow-xs shadow-black/5 transition-[color,box-shadow] text-foreground placeholder:text-muted-foreground/80 
    focus-visible:ring-ring/30  focus-visible:border-ring focus-visible:outline-none focus-visible:ring-[3px] 
    disabled:cursor-not-allowed disabled:opacity-50 [&[readonly]]:opacity-70 
    file:h-full [&[type=file]]:py-0 file:border-solid file:border-border file:bg-transparent 
    file:font-medium file:not-italic file:text-foreground file:p-0 file:border-0 file:border-e
    aria-invalid:border-destructive/60 aria-invalid:ring-destructive/10 dark:aria-invalid:border-destructive dark:aria-invalid:ring-destructive/20
  `,
  {
    variants: {
      size: {
        lg: 'h-10 px-4 text-sm rounded-md file:pe-4 file:me-4',
        md: 'h-8.5 px-3 text-[0.8125rem] leading-(--text-sm--line-height) rounded-md file:pe-3 file:me-3',
        sm: 'h-7 px-2.5 text-xs rounded-md file:pe-2.5 file:me-2.5',
      },
    },
    defaultVariants: {
      size: 'md',
    },
  },
);

export const Calender = ({ ...props }) => {
  const t = useTranslations('public');
  const [date, setDate] = useState<DateObject | null>(props.value ?? null);
  const lang = useLocale();
  const calen = lang === 'fa' ? persian : gregorian;
  const local = lang === 'fa' ? persian_fa : gregorian_en;

  const handleChange = (value: DateObject) => {
    setDate(value);
    props.onChange(value);
  };
  return (
    <div className="w-full">
      <DatePicker
        value={date}
        onChange={handleChange}
        calendar={calen}
        locale={local}
        placeholder={t('choose_date')}
        containerClassName="w-full"
        inputClass={cn(inputVariants())}
      />
    </div>
  );
};
