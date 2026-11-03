import Image from 'next/image';
import { decimalPipe } from '@/helper/decimalPipe';
import { useTranslations } from 'next-intl';
import { CurrencyStock } from '@/types/stock';
import { CardContent } from '@/components/ui/card';
import { NoData } from '@/components/ui/no-data';
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import { DialogSave } from './dialog-save';

interface Props {
  currencies: CurrencyStock[];
  metaLink: string;
}

export const TableCurrencies = ({ metaLink, currencies }: Props) => {
  const t = useTranslations('stock');

  return (
    <CardContent className=" pb-3 p-0 rounded-xl w-80 sm:w-96 md:w-[45rem] xl:w-auto">
      {currencies?.length === 0 && (
        <div className="py-5">
          <NoData />
        </div>
      )}

      <Table className=" text-sm">
        <TableBody>
          {currencies?.map((currency) => (
            <TableRow key={currency.id}>
              <TableCell className="py-2 w-40 min-w-60 md:w-72">
                <div className="flex items-center gap-x-1">
                  <div className="flex items-center gap-x-3">
                    <Image
                      src={currency.currency.icon}
                      width={24}
                      height={24}
                      className="w-6 h-6 rounded-full"
                      alt="icon-currency"
                    />
                    <span className="pt-px">{currency.currency.name}</span>
                  </div>
                  <small className="text-muted-foreground pt-px">
                    ({currency.currency.symbol})
                  </small>
                </div>
              </TableCell>
              <TableCell className="py-2 w-32 min-w-60 md:w-auto">
                <div className="flex flex-col gap-2">
                  <span className="text-muted-foreground text-xs">
                    {t('interest_min')} :
                  </span>
                  <div className="flex gap-x-2">
                    {decimalPipe(currency.min_amount, 4)}
                  </div>
                </div>
              </TableCell>
              <TableCell className="py-2 w-32 min-w-60 md:w-auto">
                <div className="flex flex-col gap-2">
                  <span className="text-muted-foreground text-xs">
                    {t('interest_time')} :
                  </span>
                  <div className="flex items-center gap-1">
                    {currency.min_day}
                    <small>{t('day')}</small>
                  </div>
                </div>
              </TableCell>
              <TableCell className="py-2 w-32 min-w-60 md:w-auto">
                <div className="flex flex-col gap-2">
                  <span className="text-muted-foreground text-xs">
                    {t('profit_dilay_amount')} :
                  </span>
                  <div className="flex gap-x-2 text-green font-semibold">
                    %{currency.daily_profit_percent}
                  </div>
                </div>
              </TableCell>
              <TableCell className="py-2 min-w-32 md:w-auto flex justify-end text-center">
                <DialogSave currency={currency} metaLink={metaLink} />
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </CardContent>
  );
};
