import { lazy, useCallback, useContext, useEffect, useState } from 'react';
import Image from 'next/image';
import { decimalPipe } from '@/helper/decimalPipe';
import { calculateSwap } from '@/helper/swap/transfer-amount';
import DefaultIcon from '@/public/media/images/currency-icon.webp';
import { SwapContext } from '@/store/swap';
import { ChevronDown } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { TransferType } from '@/types/swap';
import InputCurrency from '@/components/ui/input-currency';
import { DECIMAL_BALANCE } from './config';

const DialogCurrencies = lazy(() => import('./dialog-currencies'));

export const SectionPayment = () => {
    const t = useTranslations('swap');
    const [open, setOpen] = useState(false);
    const {
        currencies,
        config,
        inputReceive,
        inputPayment,
        selectedReceive,
        setInputPayment,
        setSelectedPayment,
        selectedPayment,
        changeInput,
        setChangeInput,
        decimaPay,
    } = useContext(SwapContext);

    useEffect(() => {
        if (changeInput === TransferType.PAYMENT) return;
        const value = calculateSwap(
            config,
            currencies,
            inputReceive,
            selectedPayment,
            selectedReceive,
            'payment',
        );
        setInputPayment(value);
    }, [
        inputReceive,
        currencies,
        changeInput,
        selectedReceive,
        selectedPayment,
        config,
        decimaPay,
        setInputPayment,
    ]);

    const handleMaxBalnace = () => {
        setChangeInput(TransferType.PAYMENT);
        setInputPayment(selectedPayment.currency.balance);
    };

    const decimalBalance =
        selectedPayment?.currency.symbol === 'IRT' ? 0 : DECIMAL_BALANCE;

    const min_amount = Number(selectedPayment?.currency.min_amount) / Number(selectedPayment?.currency.price);

    return (
        <>
            <div
                className={`px-4 py-2 bg-secondary/30 border rounded-lg flex justify-between gap-x-2 
                    ${changeInput === TransferType.PAYMENT && 'border-secondary-foreground/40'}`}
            >
                <div className="flex flex-col flex-1">
                    <span className="text-secondary-foreground text-xs">
                        {t('balance_you')} :{' '}
                        {decimalPipe(selectedPayment?.currency?.balance, decimalBalance)}
                    </span>
                    <InputCurrency
                        className="border-0 focus:shadow-none focus:border-0 !w-full focus:outline-0 text-lg my-2"
                        placeholder="0"
                        value={inputPayment}
                        onFocus={() => setChangeInput(TransferType.PAYMENT)}
                        onValueChange={setInputPayment}
                        decimalScale={decimaPay}
                        decimalsLimit={decimaPay}
                    />
                    <div
                        onClick={handleMaxBalnace}
                        className="bg-background border text-secondary-foreground rounded-sm w-10 text-2sm flex items-center cursor-pointer justify-center"
                    >
                        Max
                    </div>
                </div>
                <div className="flex flex-col items-end">
                    <span className="text-secondary-foreground text-xs">
                        {t('payment_you')}
                    </span>
                    <div
                        className="flex mt-1 cursor-pointer justify-between bg-card border h-9 text-sm items-center px-2 rounded-lg min-w-32"
                        onClick={() => setOpen(true)}
                    >
                        <div className="flex items-center gap-x-1">
                            <Image
                                src={selectedPayment?.currency.icon ?? DefaultIcon}
                                width={24}
                                height={24}
                                className="rounded-full w-6 h-6"
                                alt="coin"
                            />
                            <span className="pt-1">{selectedPayment?.currency.symbol}</span>
                        </div>
                        <ChevronDown size={20} />
                    </div>
                    <div className='mt-2 flex text-secondary-foreground text-xs'>
                        <span>{t("min_amount")} :</span>
                        <span>{decimalPipe(min_amount, 8)}</span>
                    </div>

                    <DialogCurrencies
                        open={open}
                        setOpen={setOpen}
                        selected={setSelectedPayment}
                    />
                </div>
            </div>
        </>
    );
};
