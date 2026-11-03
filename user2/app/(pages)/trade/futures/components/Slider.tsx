'use client';

import { useMemo } from 'react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { FormType } from './constants';

interface Props {
  type: FormType;
  marketPrice: string;
  enterPrice?: string;
  balance: number;
  quantity?: string;
  setInputAmount: (value: string) => void;
}

const clamp = (n: number, min = 0, max = 100) =>
  Math.min(Math.max(n, min), max);

const TOOLTIP_OFFSET = 12;
const MARKERS = [0, 25, 50, 75, 100] as const;

const resolveSliderPrice = (
  type: FormType,
  marketPrice: string,
  enterPrice?: string,
) => {
  if (type === FormType.Market || type === FormType.StopMarket) {
    return marketPrice;
  }

  return enterPrice ?? '';
};

export const Slider = ({
  type,
  marketPrice,
  enterPrice,
  balance,
  quantity,
  setInputAmount,
}: Props) => {
  const t = useTranslations('spot');

  const sliderPrice = useMemo(
    () => resolveSliderPrice(type, marketPrice, enterPrice),
    [type, marketPrice, enterPrice],
  );

  const priceNum = Number(sliderPrice);
  const amount = Number(quantity ?? 0);
  const invalidPrice = !Number.isFinite(priceNum) || priceNum <= 0;

  const value = useMemo(() => {
    if (!balance || !amount || invalidPrice) {
      return 0;
    }

    const rawPercent = ((priceNum * amount) / balance) * 100;
    const roundNumber = Math.floor(rawPercent);

    return clamp(roundNumber > 100 ? 100 : roundNumber);
  }, [amount, balance, invalidPrice, priceNum]);

  const position = value > 50 ? value - 2 : value;

  const tooltipStyle = useMemo(
    () =>
      ({
        '--rangeTooltip': `calc(${position}% - ${TOOLTIP_OFFSET}px)`,
      }) as React.CSSProperties,
    [position],
  );

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (balance <= 0) {
      toast.error(t('invalid_balance'));
      return;
    }

    if (invalidPrice) {
      return;
    }

    const percent = Number(e.currentTarget.value) / 100;
    const newAmount = (balance * percent) / priceNum;

    if (!Number.isFinite(newAmount)) {
      return;
    }

    setInputAmount(String(newAmount));
  };

  return (
    <div className="silder-range-input mt-5 relative">
      <span
        className="tooltip-slider absolute w-8 h-5 bg-mono text-background justify-center items-center text-xs rounded-sm -top-10"
        style={tooltipStyle}
      >
        {value}%
      </span>
      <input
        type="range"
        min={0}
        max={100}
        value={value}
        disabled={balance <= 0}
        onChange={handleChange}
        className="w-full h-1 inset-0 absolute z-[2] mt-[-2px]"
      />
      <ul
        className="flex items-center justify-between relative h-px bg-muted"
        style={{ '--width': `${value}%` } as React.CSSProperties}
      >
        {MARKERS.map((percent) => (
          <li
            key={percent}
            className={`w-1.5 h-1.5 rotate-45 border ${
              value >= percent ? 'bg-mono border-mono' : 'bg-background'
            }`}
          />
        ))}
      </ul>
    </div>
  );
};
