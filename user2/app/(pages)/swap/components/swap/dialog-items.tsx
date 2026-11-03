/* eslint-disable @typescript-eslint/no-explicit-any */

import { useContext } from 'react';
import Image from 'next/image';
import { decimalPipe } from '@/helper/decimalPipe';
import DefaultIcon from '@/public/media/images/currency-icon.webp';
import { SwapContext } from '@/store/swap';
import { SwapCurrency } from '@/types/swap';

interface Props {
  item: SwapCurrency;
  selected: (item: any) => void;
  closeDialog: () => void;
}

export const ListItems = ({ item, selected, closeDialog }: Props) => {
  const { setInputPayment, setInputReceive, setAllCurrencies } =
    useContext(SwapContext);

  const changes = (value: number) => {
    const isMinus = Math.sign(value);
    return (
      <span
        className={`w-1/3 text-end text-green ${isMinus === -1 && '!text-destructive'}`}
      >
        {decimalPipe(value, 2)} %
      </span>
    );
  };

  const handleSelect = () => {
    selected(item);
    setInputPayment('');
    setInputReceive('');

    setAllCurrencies((prev) => {
      const exists = prev.some(
        (currency) => currency.currency_id === item.currency.currency_id,
      );

      if (!exists) {
        return [
          ...prev,
          {
            ...item.currency,
          },
        ];
      }
      return prev;
    });
    closeDialog();
  };

  return (
    <li
      className="flex items-center justify-between text-sm py-2 hover:bg-secondary px-2 md:px-4 cursor-pointer"
      onClick={handleSelect}
    >
      <div className="flex items-center space-x-2 w-2/5">
        <Image
          src={item.currency.icon ?? DefaultIcon}
          width={30}
          height={30}
          className="rounded-full"
          alt="coin"
        />
        <div className="flex flex-col">
          <span className="text-foreground font-semibold text-base">
            {item.currency.symbol}
          </span>
          <span className="text-secondary-foreground text-sm font-medium">
            {item.currency.name}
          </span>
        </div>
      </div>
      <div className="w-3/5 flex justify-between">
        <span className="w-2/5 hidden md:block">
          {decimalPipe(item.currency.balance, 8)}
        </span>
        <span className="w-2/5">
          {decimalPipe(item.last, item.currency.decimal)}
        </span>
        <span>{changes(item.changePercent)}</span>
      </div>
    </li>
  );
};
