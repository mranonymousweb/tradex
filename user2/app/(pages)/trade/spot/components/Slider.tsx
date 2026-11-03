'use client';

import { useMemo, useState } from 'react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';

enum OrderSide {
  Buy = 1,
  Sell = 2,
}

enum MarketAmountType {
  Quote = 2,
  Base = 1,
}

interface Props {
  type: MarketAmountType;
  side: OrderSide;
  inputPrice?: string;
  balance: number;
  inputAmount?: string;
  setInputAmount: (amount: string) => void;
  basedValue?: boolean;
}

const clamp = (n: number, min = 0, max = 100) =>
  Math.min(Math.max(n, min), max);

const TOOLTIP_OFFSET = 12;

export const Slider = ({
  type,
  side,
  inputPrice,
  balance,
  inputAmount,
  setInputAmount,
  basedValue = false,
}: Props) => {
  const t = useTranslations('spot');
  const [showTooltip, setShowTooltip] = useState(false);

  const price = Number(inputPrice);
  const amount = Number(inputAmount ?? 0);
  const invalidPrice = !Number.isFinite(price) || price <= 0;
  const isSellBaseMode =
    side === OrderSide.Sell && type === MarketAmountType.Base;

  const value = useMemo(() => {
    if (
      !balance ||
      Number.isNaN(amount) ||
      ((invalidPrice && !basedValue) || (invalidPrice && isSellBaseMode))
    ) {
      return 0;
    }

    const normalizedAmount =
      basedValue && isSellBaseMode ? amount / price : amount;

    const total = basedValue
      ? normalizedAmount / balance
      : (price * amount) / balance;

    return clamp(total * 100);
  }, [amount, balance, price, invalidPrice, basedValue, isSellBaseMode]);

  const tooltipStyle = useMemo(
    () =>
      ({
        '--rangeTooltip': `calc(${value}% - ${TOOLTIP_OFFSET}px)`,
      }) as React.CSSProperties,
    [value],
  );

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (balance <= 0) {
      toast.error(t('invalid_balance'));
      return;
    }

    if ((!basedValue && invalidPrice) || (isSellBaseMode && invalidPrice)) {
      toast.error(t('required_price'));
      return;
    }

    const percent = Number(e.currentTarget.value) / 100;
    const newAmount = basedValue
      ? balance * percent
      : (balance * percent) / price;

    if (side === OrderSide.Buy) {
      setInputAmount(String(newAmount));
      return;
    }

    const nextValue =
      type === MarketAmountType.Base ? newAmount * price : newAmount;
    setInputAmount(String(nextValue));
  };

  return (
    <div className="silder-range-input my-5 relative">
      <span
        className={`tooltip-slider absolute w-8 h-5 bg-mono text-background justify-center items-center text-xs rounded-sm -top-10 transition-opacity duration-200 ${
          showTooltip ? 'opacity-100' : 'pointer-events-none opacity-0'
        }`}
        style={tooltipStyle}
      >
        {Math.round(value)}%
      </span>

      <input
        type="range"
        min={0}
        max={100}
        value={value}
        onChange={handleChange}
        onMouseEnter={() => setShowTooltip(true)}
        onMouseLeave={() => setShowTooltip(false)}
        onTouchStart={() => setShowTooltip(true)}
        onTouchEnd={() => setShowTooltip(false)}
        className="w-full h-1 inset-0 absolute z-[2] mt-[-2px]"
      />

      <ul
        className="flex items-center justify-between relative h-px bg-muted"
        style={{ '--width': `${value}%` } as React.CSSProperties}
      >
        {[0, 25, 50, 75, 100].map((p) => (
          <li
            key={p}
            className={`w-1.5 h-1.5 rotate-45 border ${
              value >= p ? 'bg-mono border-mono' : 'bg-background'
            }`}
          />
        ))}
      </ul>
    </div>
  );
};
