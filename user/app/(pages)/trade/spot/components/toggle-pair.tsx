import { TradePair } from '@/types/spot';

interface Props {
  item: TradePair;
  isActiveTab: string;
  setIsActiveTab: (active: string) => void;
}

export const TogglePair = ({ item, isActiveTab, setIsActiveTab }: Props) => {
  const handleToggle = (symbolBase: string) => {
    setIsActiveTab(symbolBase);
  };

  return (
    <span
      key={item.id}
      className={`
                    pb-2 cursor-pointer hover:text-mono 
                    ${isActiveTab === item.baseCurrency.symbol && 'active_tab'}`}
      onClick={() => handleToggle(item.baseCurrency.symbol)}
    >
      {item.baseCurrency.symbol}
    </span>
  );
};
