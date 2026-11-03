import { useContext, useEffect, useState } from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { SwapContext } from '@/store/swap';
import { useTranslations } from 'next-intl';
import { cn } from '@/lib/utils';
import { TableBody, TableCell, TableRow } from '@/components/ui/table';
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from '@/components/ui/tooltip';
import { ReferencePrice } from './reference-price';

interface ILatestPaymentItem {
  status: string;
  logo?: boolean;
  info: number | string;
}
type ILatestPaymentItems = Array<ILatestPaymentItem>;

function Table({
  className,
  ...props
}: React.HTMLAttributes<HTMLTableElement>) {
  return (
    <div data-slot="table-wrapper" className="w-full overflow-auto">
      <table
        data-slot="table"
        className={cn(
          'w-full caption-bottom text-foreground text-sm',
          className,
        )}
        {...props}
      />
    </div>
  );
}
export const CardResult = () => {
  const {
    config,
    selectedReceive,
    selectedPayment,
    inputReceive,
    decimaFee,
    decimaFinal,
  } = useContext(SwapContext);
  const t = useTranslations('swap');
  const [fee, setFee] = useState(0);
  const [swapFee, setSwapFee] = useState('0');
  const [total, setTotal] = useState(0);

  useEffect(() => {
    const receive = Number(inputReceive) || 0;
    const swapFee = Number(config?.swap_fee) || 0;
    const MAX_FEE = config?.swap_fee_max;
    const priceReceive = selectedReceive?.last ?? 0;

    const fee_value = swapFee / 100;
    const feeAmount = receive * fee_value;
    const maxBuy = feeAmount * priceReceive;

    const equivalentFee = MAX_FEE / priceReceive;
    const finalFee = maxBuy > MAX_FEE ? equivalentFee : feeAmount;
    const swap_fee = maxBuy > MAX_FEE ? '' : `(${config?.swap_fee}%)`;
    const finalAmount = receive - finalFee;

    setFee(finalFee);
    setSwapFee(swap_fee);
    setTotal(finalAmount);
  }, [inputReceive, config, selectedReceive]);

  const tables: ILatestPaymentItems = [
    {
      status: `${t('fee')}  ${swapFee}`,
      info: `${selectedReceive.currency.symbol} ${decimalPipe(fee, decimaFee)}`,
    },
    {
      status: t('received_total'),
      info: `${selectedReceive.currency.symbol} ${decimalPipe(total, decimaFinal)}`,
    },
  ];

  const renderItem = (table: ILatestPaymentItem, index: number) => {
    return (
      <TableRow key={index} className="border-0">
        <TableCell className="text-2sm text-secondary-foreground pb-5 py-2">
          {table.status}
        </TableCell>
        <TableCell className="flex justify-end gap-2.5 text-sm text-foreground py-2">
          {table.info}
        </TableCell>
      </TableRow>
    );
  };
  return (
    <div className="bg-secondary/30 border rounded-lg text-sm">
      <div className="px-4 flex justify-between border-b py-2 items-center">
        <Tooltip>
          <TooltipTrigger>
            <i className="ki-filled ki-information-3 text-lg"></i>
          </TooltipTrigger>
          <TooltipContent>
            {t('tooltip')}
            <span className="mx-1">{config.swap_fee_max}</span>
            {t('tether')}
          </TooltipContent>
        </Tooltip>
        <div className="flex">
          <div className="flex gap-x-2">
            {selectedReceive.currency.symbol}
            <ReferencePrice inputAmount={'1'} />
          </div>
          <span className="mx-2">=</span>
          <div className="flex">
            <span>{selectedPayment.currency.symbol}</span>
            <span>1</span>
          </div>
        </div>
      </div>
      <Table>
        <TableBody>
          {tables.map((table, index) => {
            return renderItem(table, index);
          })}
        </TableBody>
      </Table>
    </div>
  );
};
