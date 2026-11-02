import { CSSProperties, useContext, useMemo, useState } from "react";
import { handleDecimal } from "@/helper/decimalPipe";
import { MainSpotContext } from "@/store/trade-spot";
import { useTranslations } from "next-intl";

enum OrderSide {
    BUY = 1,
    SELL = 2,
}

export const ItemsOrderBook = ({
    data,
    reverseAccumulate = false,
}: {
    data: any[];
    reverseAccumulate?: boolean;
}) => {
    const t = useTranslations("spot");
    const { setTotalOrders, pair } = useContext(MainSpotContext);
    const [hoverIndex, setHoverIndex] = useState<number | null>(null);

    const base = pair.split("_")[1];
    const symbol = pair.split("_")[0];

    const cumulativeTotals = useMemo(() => {
        if (!data) return [];

        if (!reverseAccumulate) {
            let amount = 0;
            let total = 0;
            let price = 0;
            return data.map((order) => {
                amount += +order.amount;
                total += +order.total;
                price = +order.price;
                return { amount, total, price };
            });
        } else {
            const totalAmount = data.reduce((sum, o) => sum + +o.amount, 0);
            const totalValue = data.reduce((sum, o) => sum + +o.total, 0);

            let remainingAmount = totalAmount;
            let remainingTotal = totalValue;

            return data.map((order, i) => {
                const result = {
                    amount: remainingAmount,
                    total: remainingTotal,
                    price: +order.price,
                };
                remainingAmount -= +order.amount;
                remainingTotal -= +order.total;
                return result;
            });
        }
    }, [data, reverseAccumulate]);

    const totals = hoverIndex !== null ? cumulativeTotals[hoverIndex] : null;

    const handleSelect = () => {
        setTotalOrders(totals);
    };

    return data?.map((order, index) => {
        const progressPercent = (order.filled_amount / order.amount) * 100;
        return (
            <li
                key={index}
                className={`relative flex items-center justify-between text-xs py-1 
          ${order.side === 1 ? "progress_buy" : "progress_sell"}`}
                style={
                    { "--progress": `${Math.round(progressPercent)}%` } as CSSProperties
                }
                onMouseEnter={() => setHoverIndex(index)}
                onMouseLeave={() => setHoverIndex(null)}
                onClick={handleSelect}
            >
                <div className="flex items-center justify-between w-full">
                    <span
                        className={`min-w-20 text-start ${order.side === OrderSide.BUY ? "text-green" : "text-destructive"}`}
                    >
                        {handleDecimal(String(order.price), base)}
                    </span>
                    <span className="min-w-10 text-end">
                        {handleDecimal(String(order.amount))}
                    </span>
                    <span className="min-w-20 text-end">
                        {handleDecimal(String(order.total), base)}
                    </span>
                </div>
                <div
                    className={`w-full absolute top-0 right-0 h-6 
            ${hoverIndex !== null &&
                        (order.side === OrderSide.BUY
                            ? index <= hoverIndex
                            : index >= hoverIndex) &&
                        `${order.side === OrderSide.BUY ? "bg-green/15" : "bg-destructive/15"}`
                        }`}
                />

                {/*Tooltip*/}
                {hoverIndex === index && (
                    <div
                        className="
              absolute z-100 left-[-190px] w-44
              whitespace-normal break-words
              rounded-lg bg-background
              py-1.5 px-3 border border-muted-foreground shadow-xl
              font-sans text-sm font-normal
              flex flex-col items-end text-foreground"
                    >
                        {/* Arrow */}
                        <div
                            className="
                            absolute
                            top-1/2
                            -right-2
                            -translate-y-1/2
                            w-0 h-0
                            border-t-[8px]
                            border-b-[8px]
                            border-l-[8px]
                            border-t-transparent
                            border-b-transparent
                            border-l-muted-foreground"
                        />

                        <div className="flex flex-wrap items-center justify-between w-full">
                            <span className="flex items-center gap-x-1 text-xs">
                                <small>{base}</small>
                                <span>{handleDecimal(String(totals?.price))}</span>
                            </span>
                            <span className="text-xs">: {t("price")}</span>
                        </div>

                        <div className="flex flex-wrap items-center justify-between w-full my-1">
                            <span className="flex items-center gap-x-1 text-xs">
                                <small>{symbol}</small>
                                <span>{handleDecimal(String(totals?.amount))}</span>
                            </span>
                            <span className="text-xs">: {t("total")}</span>
                        </div>

                        <div className="flex flex-wrap items-center justify-between w-full">
                            <span className="flex items-center gap-x-1 text-xs">
                                <small>{base}</small>
                                <span>{handleDecimal(String(totals?.total))}</span>
                            </span>
                            <span className="text-xs">: {t("total_value")}</span>
                        </div>
                    </div>
                )}
            </li>
        );
    });
};
