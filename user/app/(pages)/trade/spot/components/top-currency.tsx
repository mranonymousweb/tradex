import { decimalPipe } from '@/helper/decimalPipe';
import { currenciesPrice } from '@/helper/spot/currencyPrice';
import { DotsLoader } from '@/components/ui/dots-loader';

export const TopCurrency = () => {
  const { currencies, isLoading } = currenciesPrice();
  const list = currencies?.slice(0, 4);

  if (isLoading) {
    return (
      <div className="w-full py-2.5 h-10 bg-background">
        <DotsLoader />
      </div>
    );
  }

  return (
    <div
      className="hidden lg:flex gap-x-3 items-center h-10 flex-wrap bg-card text-2sm font-medium py-2 px-3 rounded-r-lg"
      dir="ltr"
    >
      {list?.map((item, i) => {
        const symbol = item.pairs.replace(/_/g, '');
        const percent = Math.sign(item.changePercent);
        const textColor = percent === -1 ? 'text-destructive' : 'text-green';

        return (
          <div key={i} className="flex items-center gap-x-1.5 min-w-[100px]">
            <span>{symbol}</span>
            <span className={textColor}>
              {decimalPipe(item.changePercent, 2)}%
            </span>
          </div>
        );
      })}
    </div>
  );
};
