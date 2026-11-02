import Image from 'next/image';
import { decimalPipe } from '@/helper/decimalPipe';
import { useTranslations } from 'next-intl';
import { CurrencyConversion } from '@/types/wallet';
import { Button } from '@/components/ui/button';
import { CardContent } from '@/components/ui/card';
import { NoData } from '@/components/ui/no-data';
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import { DialogVerify } from './dialog-verify';

interface Props {
  currencies: CurrencyConversion[];
}

export const TableCurrencies = ({ currencies }: Props) => {
  const t = useTranslations('wallet.conversion');
  const buttonEl = (
    <Button
      variant="outline"
      className="text-primary border-primary mx-2 md:mx-0"
    >
      {t('tether_transfrom')}
    </Button>
  );

  return (
    <CardContent className="kt-scrollable-x-auto pb-3 p-0 rounded-xl ">
      {(currencies?.length === 0 || !currencies) && (
        <div className="py-5">
          <NoData />
        </div>
      )}

      <Table className="table-fixed align-middle text-sm">
        <TableBody>
          {currencies?.map((currency) => (
            <TableRow key={currency.id}>
              <TableCell className="py-2 w-40 min-w-60 md:w-72">
                <div className="flex items-center gap-x-1">
                  <div className="flex items-center gap-x-3">
                    <Image
                      src={currency.icon}
                      width={24}
                      height={24}
                      className="w-6 h-6 rounded-full"
                      alt="icon-currency"
                    />
                    <span className="pt-px">{currency.name}</span>
                  </div>
                  <small className="text-muted-foreground pt-px">
                    ({currency.symbol})
                  </small>
                </div>
              </TableCell>
              <TableCell className="py-2 w-full min-w-60 md:w-auto">
                <div className="flex flex-col md:flex-row gap-x-2">
                  <span className="text-muted-foreground text-xs">
                    {t('balance_you')}:
                  </span>
                  <div className="flex gap-x-2">
                    {decimalPipe(currency.balance, 14)}
                  </div>
                </div>
              </TableCell>
              <TableCell className="py-2 min-w-32 md:w-auto flex justify-end text-center">
                <DialogVerify
                  id={currency.id}
                  buttonElement={buttonEl}
                  type="single"
                />
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </CardContent>
  );
};
