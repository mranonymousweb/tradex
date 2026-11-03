import { useContext, useEffect, useState } from 'react';
import { handleDecimal } from '@/helper/decimalPipe';
import { MainSpotContext } from '@/store/trade-spot';
import { useTranslations } from 'next-intl';
import { useIsTablet } from '@/hooks/use-tablet';
import { NoData } from '@/components/ui/no-data';
import { ItemsOrderBook } from './items-order-book';
import { Trades } from './trades';
import { DotsLoader } from '@/components/ui/dots-loader';

enum TypeSow {
    BOTH = 'BOTH',
    LONG = 'LONG',
    SHORT = 'SHORT',
}

export const OrderBooks = () => {
    const t = useTranslations('spot');
    const { pair, price, orders, isOrdersLoading } = useContext(MainSpotContext);
    const isTablet = useIsTablet();
    const symbol = pair.split('_')[0];
    const basePair = pair.split('_')[1];

    const [typeShow, setTypeShow] = useState<TypeSow>(TypeSow.BOTH);
    const [width, setWidth] = useState(1280);

    useEffect(() => {
        const onResize = () => setWidth(window.innerWidth);
        onResize();
        window.addEventListener('resize', onResize);
        return () => window.removeEventListener('resize', onResize);
    }, []);

    const lastOrder = orders ? orders.last : null;
    const lastSide = orders?.side;
    const COUNT =
        typeShow === TypeSow.BOTH ? (width <= 1024 ? 4 : 6) : 12;
    const closeOrderCount =
        width < 768 ? 6 : width <= 1280 ? 10 : 6;
    const buyOrderLimit = orders?.buyers?.slice(0, COUNT);
    const shortOrderLimit = orders?.sellers?.slice(0, COUNT);
    const closeOrderLimit = orders?.done?.slice(0, closeOrderCount);

    const priceLastOrder = (
        <div className="flex items-center my-1">
            <span
                className={`text-lg font-medium ${lastSide === 1 ? 'text-green' : 'text-destructive'}`}
            >
                {lastOrder ? handleDecimal(lastOrder, basePair) : 0}
            </span>
            <svg
                className={`w-[1em] h-[1em] arrow-icon text-[16px] mb-1 mx-1 ${lastSide === 1 ? 'text-green' : 'text-destructive'}`}
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
                style={{
                    transform: `${lastSide === 1 ? 'rotate(180deg)' : 'rotate(0deg)'}`,
                }}
            >
                <path
                    fillRule="evenodd"
                    clipRule="evenodd"
                    d="M12 20.999l7.071-7.071-1.768-1.768-4.054 4.055V2.998h-2.5v13.216L6.696 12.16l-1.768 1.768 7.07 7.071H12z"
                    fill="currentColor"
                ></path>
            </svg>
            <span className="text-muted-foreground text-xs ms-2">
                {handleDecimal(String(price), basePair)}
            </span>
        </div>
    );

    const orderListContent = (
        items: typeof buyOrderLimit,
        reverseAccumulate = false,
    ) => {
        if (isOrdersLoading) {
            return (
                <div className="flex items-center justify-center h-full">
                    <DotsLoader />
                </div>
            );
        }

        if (!items || items.length === 0) {
            return (
                <div className="flex items-center justify-center h-full">
                    <NoData />
                </div>
            );
        }

        return (
            <ItemsOrderBook
                data={reverseAccumulate ? items.reverse() : items}
                reverseAccumulate={reverseAccumulate}
            />
        );
    };

    return (
        <>
            <div
                className="flex flex-col flex-1 bg-card md:rounded-lg shadow-xs md:mt-1 xl:mt-0"
                dir="ltr"
            >
                <div className="border-b-1 px-4 py-2 font-medium text-sm hidden xl:block">
                    <h2>{t('order_book')}</h2>
                </div>
                <div className="flex flex-col p-3 md:h-[18rem] xl:h-[24.5rem]">
                    <div className="flex items-center gap-x-2">
                        <div
                            className="opacity-100"
                            onClick={() => setTypeShow(TypeSow.BOTH)}
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                            >
                                <path
                                    d="M2.66663 2.66699L7.33329 2.66699L7.33329 7.33366L2.66663 7.33366L2.66663 2.66699Z"
                                    fill="red"
                                ></path>
                                <path
                                    d="M2.66663 8.66699L7.33329 8.66699L7.33329 13.3337L2.66663 13.3337L2.66663 8.66699Z"
                                    fill="green"
                                ></path>
                                <path
                                    fillRule="evenodd"
                                    clipRule="evenodd"
                                    d="M8.66663 2.66699L13.3333 2.66699L13.3333 5.33366L8.66663 5.33366L8.66663 2.66699ZM8.66663 6.66699L13.3333 6.66699L13.3333 9.33366L8.66663 9.33366L8.66663 6.66699ZM13.3333 10.667L8.66663 10.667L8.66663 13.3337L13.3333 13.3337L13.3333 10.667Z"
                                    fill="gray"
                                ></path>
                            </svg>
                        </div>
                        <div
                            className="opacity-50"
                            onClick={() => setTypeShow(TypeSow.LONG)}
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                            >
                                <g>
                                    <path
                                        d="M2.66663 2.66699L7.33329 2.66699L7.33329 13.3337L2.66663 13.3337L2.66663 2.66699Z"
                                        fill="green"
                                    ></path>
                                    <path
                                        fillRule="evenodd"
                                        clipRule="evenodd"
                                        d="M8.66663 2.66699L13.3333 2.66699L13.3333 5.33366L8.66663 5.33366L8.66663 2.66699ZM8.66663 6.66699L13.3333 6.66699L13.3333 9.33366L8.66663 9.33366L8.66663 6.66699ZM13.3333 10.667L8.66663 10.667L8.66663 13.3337L13.3333 13.3337L13.3333 10.667Z"
                                        fill="gray"
                                    ></path>
                                </g>
                            </svg>
                        </div>
                        <div
                            className="opacity-50"
                            onClick={() => setTypeShow(TypeSow.SHORT)}
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                            >
                                <g>
                                    <path
                                        d="M2.66663 2.66699L7.33329 2.66699L7.33329 13.3337L2.66663 13.3337L2.66663 2.66699Z"
                                        fill="red"
                                    ></path>
                                    <path
                                        fillRule="evenodd"
                                        clipRule="evenodd"
                                        d="M8.66663 2.66699L13.3333 2.66699L13.3333 5.33366L8.66663 5.33366L8.66663 2.66699ZM8.66663 6.66699L13.3333 6.66699L13.3333 9.33366L8.66663 9.33366L8.66663 6.66699ZM13.3333 10.667L8.66663 10.667L8.66663 13.3337L13.3333 13.3337L13.3333 10.667Z"
                                        fill="gray"
                                    ></path>
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div className="flex items-cener justify-between text-xs text-muted-foreground mt-3 mb-1">
                        <span>
                            {t('price')} ({basePair})
                        </span>
                        <span>
                            {t('amount')} ({symbol})
                        </span>
                        <span>
                            {t('total')} ({basePair})
                        </span>
                    </div>
                    {typeShow !== TypeSow.BOTH && priceLastOrder}
                    <ul
                        className={`
                        content-end ${typeShow === TypeSow.BOTH && 'md:min-h-24 xl:min-h-36 overflow-hidden xl:overflow-visible'}
                        ${typeShow === TypeSow.LONG && 'hidden'}`}
                    >
                        {orderListContent(shortOrderLimit, true)}
                    </ul>
                    {typeShow === TypeSow.BOTH && priceLastOrder}
                    <ul
                        className={`md:min-h-24 xl:min-h-36 overflow-hidden xl:overflow-visible ${typeShow === TypeSow.SHORT && 'hidden'}`}
                    >
                        {orderListContent(buyOrderLimit)}
                    </ul>
                </div>
            </div>
            {!isTablet && (
                <Trades orders={closeOrderLimit} symbol={symbol} basePair={basePair} />
            )}
        </>
    );
};
