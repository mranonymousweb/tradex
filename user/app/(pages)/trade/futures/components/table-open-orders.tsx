import { decimalPipe } from '@/helper/decimalPipe';
import { Position } from '@/types/futures';
import { NoData } from '@/components/ui/no-data';
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { CloseOrder } from './close-order';
import { UpdateTpsl } from './update-tpsl';

const getOrderMetrics = (item: Position | undefined) => {
    if (!item) return { isMinus: 0, pnl: 0, roiAmount: 0 };

    const isMinus = Math.sign(Number(item?.pnl || 0));
    const margin = Number(item?.margin);
    const pnl = Number(item?.pnl).toFixed(0);
    const roiAmount = margin === 0 ? 0 : (Number(pnl) / margin) * 100;

    return { isMinus, pnl, roiAmount, margin };
};


export const OpneOrders = ({ items }: { items: Position | undefined }) => {
    const { isMinus, pnl, roiAmount } = getOrderMetrics(items);
    const position_size = Number(items?.quantity) * Number(items?.leverage)

    return (
        <>
            {!items ? (
                <div className="py-[25px]">
                    <NoData />
                </div>
            ) : (
                <Table className="table-fixed border-separate border-spacing-0 text-xs mt-3 pb-5">
                    <TableHeader>
                        <TableRow className="!text-muted-foreground w-full">
                            <TableCell className="whitespace-nowrap w-32">Symbol</TableCell>
                            <TableCell className="whitespace-nowrap w-32">Size</TableCell>
                            <TableCell className="whitespace-nowrap w-32">
                                Entry Price
                            </TableCell>
                            <TableCell className="whitespace-nowrap w-32">
                                Breack Even Price
                            </TableCell>
                            <TableCell className="whitespace-nowrap w-32">
                                Market Price
                            </TableCell>
                            <TableCell className="whitespace-nowrap w-32">
                                Liq.Price
                            </TableCell>
                            <TableCell className="whitespace-nowrap w-32">
                                Margin Ratio
                            </TableCell>
                            <TableCell className="whitespace-nowrap w-32">Margin</TableCell>
                            <TableCell className="whitespace-nowrap w-32">
                                PNL(ROI %)
                            </TableCell>
                            <TableCell className="whitespace-nowrap w-32">TP/SL</TableCell>
                            <TableCell className="whitespace-nowrap w-32">
                                Close Position
                            </TableCell>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow>
                            <TableCell className="w-32">
                                <div
                                    className={`border-s-2 ${items.side === 1 ? 'border-green' : 'border-destructive'}`}
                                >
                                    <div className="flex flex-col px-2">
                                        <span>{items.pairs}</span>
                                        <div className="flex space-x-2 mt-1">
                                            <span className="bg-secondary text-xs rounded-sm px-1 py-[2px]">
                                                Perp
                                            </span>
                                            <span className="bg-secondary text-xs rounded-sm px-1 py-[2px]">
                                                {items.leverage}X
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell
                                className={items.side === 1 ? 'text-green' : 'text-destructive'}
                            >
                                {decimalPipe(position_size, 6)}{' '}
                                {items.main_currency_symbol}
                            </TableCell>
                            <TableCell>{decimalPipe(items.enter_price, 4)}</TableCell>
                            <TableCell>{decimalPipe(items.break_even, 5)}</TableCell>
                            <TableCell>{decimalPipe(items.market_price, 4)}</TableCell>
                            <TableCell className="text-[#C99400]">
                                {decimalPipe(items.liq_price, 2)}
                            </TableCell>
                            <TableCell>{decimalPipe(items.margin_ratio, 2)}%</TableCell>
                            <TableCell>
                                <div className="flex flex-col">
                                    <span>{decimalPipe(items.margin, 3)} USDT</span>
                                    <span>({items.margin === 1 ? 'Cross' : 'Isolated'})</span>
                                </div>
                            </TableCell>
                            <TableCell>
                                <div
                                    className={`flex flex-col ${isMinus === 1 ? 'text-green' : 'text-destructive'}`}
                                >
                                    <span>{pnl} USDT</span>
                                    <span>({roiAmount}%)</span>
                                </div>
                            </TableCell>
                            <TableCell>
                                <div className="flex items-center gap-x-2">
                                    {items.take_profit ? decimalPipe(items.take_profit, 2) : '--'}
                                    /{items.stop_loss ? decimalPipe(items.stop_loss, 2) : '--'}
                                    <UpdateTpsl key={items.id} items={items} />
                                </div>
                            </TableCell>
                            <TableCell className="text-destructive">
                                <CloseOrder id={+items.id} buttonElement="Market" />
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            )}
        </>
    );
};
