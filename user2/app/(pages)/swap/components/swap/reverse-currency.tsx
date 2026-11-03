import { useContext } from 'react';
import { SwapContext } from '@/store/swap';

export const ReverseCurrency = () => {
  const {
    inputPayment,
    inputReceive,
    setInputPayment,
    setInputReceive,
    selectedPayment,
    selectedReceive,
    setSelectedPayment,
    setSelectedReceive,
  } = useContext(SwapContext);

  const handleClick = () => {
    setSelectedPayment(selectedReceive);
    setSelectedReceive(selectedPayment);
    setInputReceive(inputPayment);
    setInputPayment(inputReceive);
  };

  return (
    <span
      onClick={handleClick}
      className="absolute border w-6 h-6 lg:w-8 lg:h-8 bg-background rounded-lg left-1/2 top-[87px] lg:top-[85px] flex items-center justify-center"
    >
      <i className="ki-filled ki-arrow-up-down text-secondary-foreground text-xs lg:text-base"></i>
    </span>
  );
};
