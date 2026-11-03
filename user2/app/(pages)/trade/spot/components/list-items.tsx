import { startTransition, useContext, useOptimistic, useState } from 'react';
import { usePathname, useRouter, useSearchParams } from 'next/navigation';
import { decimalPipe, handleDecimal } from '@/helper/decimalPipe';
import { MainSpotContext } from '@/store/trade-spot';
import { Star } from 'lucide-react';
import { updateFavorite } from '@/services/spot.services';

interface Props {
  setOpen: (op: boolean) => void;
  item: {
    id: number;
    favorite: boolean;
    pairs: string;
    price: string | number;
    changePercent: number;
  };
}

export const ListItems = ({ item, setOpen }: Props) => {
  const searchParams = useSearchParams();
  const pathname = usePathname();
  const router = useRouter();
  const [pending, setPending] = useState(false);
  const [optimisticIsLiked, setOptimisticIsLiked] = useOptimistic(
    item.favorite,
    (_, value: boolean) => value,
  );

  const { pair } = useContext(MainSpotContext);
  const basePair = pair.split('_')[1];

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

  const handleConfirm = (pairs: string) => {
    const params = new URLSearchParams(searchParams.toString());
    params.set('market', pairs);
    router.push(`${pathname}?${params.toString()}`);

    setTimeout(() => {
      setOpen(false);
    }, 500);
  };

  const handleAddFavorites = async (e: React.MouseEvent<HTMLButtonElement>) => {
    e.stopPropagation();

    if (pending) return;

    const nextValue = !optimisticIsLiked;

    startTransition(() => {
      setOptimisticIsLiked(nextValue);
    });
    setPending(true);

    try {
      await updateFavorite({
        data: {
          market_currency_id: item.id,
        },
        url: item.favorite ? '/destroy' : '',
      });

      router.refresh();
    } catch {
      setOptimisticIsLiked(!nextValue);
    } finally {
      setPending(false);
    }
  };

  return (
    <li
      className="flex items-center justify-between text-sm py-2 hover:bg-secondary hover:svg:text-white px-4 cursor-pointer"
      onClick={() => handleConfirm(item.pairs)}
    >
      <div className="flex w-2/5 space-x-1.5">
        <button onClick={(e) => handleAddFavorites(e)}>
          <Star
            fill={`${optimisticIsLiked ? '#1379f0' : '#e5e7eb'} `}
            size={17}
            strokeWidth={0}
          />
        </button>
        <span className="text-foreground text-sm font-medium">
          {item.pairs}
        </span>
        <span className="bg-secondary text-xs rounded-sm px-1 py-[2px]">
          Perp
        </span>
      </div>
      <span className="w-1/3 text-end">
        {handleDecimal(item.price, basePair)}
      </span>
      {changes(item.changePercent)}
    </li>
  );
};
