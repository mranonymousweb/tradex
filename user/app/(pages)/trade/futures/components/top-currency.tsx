import { decimalPipe } from '@/helper/decimalPipe';
import { futuresCurrencies } from '@/helper/futures/currencyPrice';
import { DotsLoader } from '@/components/ui/dots-loader';

export const TopCurrency = () => {
  const { currencies, isLoading } = futuresCurrencies('USDT');
  const list = currencies?.slice(0, 4);

  if (isLoading) {
    return (
      <div className="w-full py-2.5 bg-background h-10">
        <DotsLoader />
      </div>
    );
  }

  return (
    <div
      className={`hidden lg:flex gap-x-3 items-center h-10 flex-wrap bg-background 
                text-2sm font-medium py-2 px-3 rounded-r-lg`}
      dir="ltr"
    >
      {list?.map((currency, i) => {
        const name = currency.pairs.replace(/_/g, '');
        const percent = Math.sign(currency?.changePercent);
        const textColor = percent === -1 ? 'text-destructive' : 'text-green';

        return (
          <div key={i} className="flex items-center gap-x-1.5 min-w-[100px]">
            <span>{name}</span>
            <span className={textColor}>
              {decimalPipe(currency?.changePercent, 2)}%
            </span>
          </div>
        );
      })}
    </div>
  );
};
