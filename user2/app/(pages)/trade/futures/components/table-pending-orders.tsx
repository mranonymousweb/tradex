import { decimalPipe } from '@/helper/decimalPipe';
import { localeDate } from '@/helper/formatDate';
import { Position } from '@/types/futures';
import { useLocale } from 'next-intl';
import { NoData } from '@/components/ui/no-data';
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { CloseOrder } from './close-order';
import { Trash2 } from 'lucide-react';

enum Type {
    MARKET = 1,
    LIMIT = 2,
    STOPMARKET = 3,
    STOPLIMIT = 4,
}

enum Side {
    BUY = 1,
    SELL = 2,
}

export const PendingOrders = ({ items }: { items: Position[] | undefined }) => {
    const lang = useLocale();

    const filledValue = (size: string, price: string) => {
        const cost = +size * +price;
        return <span>{decimalPipe(cost)}</span>;
    };

    return (
        <>
            {!items?.length ? (
                <div className="py-[25px]">
                    <NoData />
                </div>
            ) : (
                <Table className="table-fixed border-separate border-spacing-0 text-xs mt-3 pb-5">
                    <TableHeader>
                        <TableRow className="!text-muted-foreground text-center">
                            <TableCell className="whitespace-nowrap w-32">Time</TableCell>
                            <TableCell className="whitespace-nowrap w-32">Symbol</TableCell>
                            <TableCell className="whitespace-nowrap w-32">Type</TableCell>
                            <TableCell className="whitespace-nowrap w-32">Side</TableCell>
                            <TableCell className="whitespace-nowrap w-32">Price</TableCell>
                            <TableCell className="whitespace-nowrap w-32">Amount</TableCell>
                            <TableCell className="whitespace-nowrap w-32">Filled</TableCell>
                            <TableCell className="whitespace-nowrap w-32">
                                Reduce Only
                            </TableCell>
                            <TableCell className="whitespace-nowrap w-32">
                                Post Only
                            </TableCell>
                            <TableCell className="whitespace-nowrap w-32">TP/SL</TableCell>
                            <TableCell className="whitespace-nowrap w-32">Close</TableCell>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {items?.map((order: Position) => (
                            <TableRow className="text-center" key={order.id}>
                                <TableCell>
                                    <div className="flex flex-col gap-y-1">
                                        <span>{localeDate(String(order.opened_at), lang)}</span>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div className="flex flex-col items-center gap-y-1">
                                        <span>{order.pairs}</span>
                                        <span className="bg-secondary text-xs inline-block rounded-sm px-1 py-[2px]">
                                            Perp
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    {+order.type === Type.MARKET
                                        ? 'market'
                                        : +order.type === Type.LIMIT
                                            ? 'limit'
                                            : +order.type === Type.STOPLIMIT
                                                ? 'Stop Limit'
                                                : 'Stop Market'}
                                </TableCell>
                                <TableCell>
                                    <span
                                        className={
                                            order.side === Side.BUY
                                                ? 'text-green'
                                                : 'text-destructive'
                                        }
                                    >
                                        {order.side === Side.BUY ? 'Buy/Long' : 'Sell/Short'}
                                    </span>
                                </TableCell>
                                <TableCell>{decimalPipe(order.enter_price, 4)}</TableCell>
                                <TableCell>
                                    {decimalPipe(order.quantity, 6)} {order.main_currency_symbol}
                                </TableCell>
                                <TableCell>
                                    {filledValue(order.quantity, order.enter_price)}{' '}
                                    {order.main_currency_symbol}
                                </TableCell>
                                <TableCell>No</TableCell>
                                <TableCell>No</TableCell>
                                <TableCell>
                                    {order.take_profit ? decimalPipe(order.take_profit ?? 0, 4) : "--"} /{' '}
                                    {order.stop_loss ? decimalPipe(order.stop_loss ?? 0, 4) : "--"}
                                </TableCell>
                                <TableCell>
                                    <CloseOrder
                                        id={+order.id}
                                        buttonElement={<Trash2 color="#ff0000" size={20} />}
                                    />
                                </TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
            )}
        </>
    );
};
