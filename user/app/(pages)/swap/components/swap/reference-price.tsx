import { useContext, useEffect, useState } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { calculateSwap } from '@/helper/swap/transfer-amount';
import { SwapContext } from '@/store/swap';

interface Props {
  inputAmount: string;
}

export const ReferencePrice = ({ inputAmount }: Props) => {
  const {
    config,
    currencies,
    selectedReceive,
    selectedPayment,
    decimaCurrency,
  } = useContext(SwapContext);
  const [value, setValue] = useState(0);

  useEffect(() => {
    const value = calculateSwap(
      config,
      currencies,
      inputAmount,
      selectedPayment,
      selectedReceive,
      'received',
    );
    setValue(+value);
  }, [currencies, config, inputAmount, selectedPayment, selectedReceive]);

  return <span>{decimalPipe(value, decimaCurrency)}</span>;
};
