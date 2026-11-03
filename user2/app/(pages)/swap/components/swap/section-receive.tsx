import { lazy, useContext, useEffect, useState } from 'react';
import Image from 'next/image';
import { decimalPipe, truncateNumber } from '@/helper/decimalPipe';
import { calculateSwap } from '@/helper/swap/transfer-amount';
import DefaultIcon from '@/public/media/images/currency-icon.webp';
import { SwapContext } from '@/store/swap';
import { ChevronDown } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { TransferType } from '@/types/swap';
import InputCurrency from '@/components/ui/input-currency';
import { DECIMAL_BALANCE } from './config';

const DialogCurrencies = lazy(() => import('./dialog-currencies'));

export const SectionReceive = () => {
    const t = useTranslations('swap');
    const [open, setOpen] = useState(false);
    const {
        currencies,
        config,
        inputReceive,
        inputPayment,
        setInputReceive,
        selectedReceive,
        selectedPayment,
        setSelectedReceive,
        changeInput,
        setChangeInput,
        decimaReceive,
    } = useContext(SwapContext);
    const [receiveBalance, setReceiveBalance] = useState<string | undefined>(
        selectedReceive?.currency.balance,
    );

    useEffect(() => {
        //update current balance
        const selectedCurrency = currencies.find(
            (cur) =>
                cur.currency.currency_id === selectedReceive.currency.currency_id,
        );
        setReceiveBalance(selectedCurrency?.currency.balance);

        if (changeInput === TransferType.RECEIVE) return;
        const value = calculateSwap(
            config,
            currencies,
            inputPayment,
            selectedPayment,
            selectedReceive,
            'received',
        );
        const num = value ? truncateNumber(Number(value), decimaReceive) : '';

        setInputReceive(num);
    }, [
        inputPayment,
        currencies,
        changeInput,
        selectedPayment,
        selectedReceive,
        config,
        decimaReceive,
        setInputReceive,
    ]);

    const decimalBalance =
        selectedPayment?.currency.symbol === 'IRT' ? 0 : DECIMAL_BALANCE;

    return (
        <div
            className={`px-4 py-2 bg-secondary/30 border rounded-lg flex justify-between gap-x-2 
                ${changeInput === TransferType.RECEIVE && 'border-secondary-foreground/40'}`}
        >
            <div className="flex flex-col flex-1 pb-3">
                <span className="text-secondary-foreground text-xs">
                    {t('balance_you')} :{' '}
                    {decimalPipe(receiveBalance ?? '0', decimalBalance)}
                </span>
                <InputCurrency
                    className="border-0 focus:shadow-none focus:border-0 !w-full focus:outline-0 text-lg my-2"
                    placeholder="0"
                    value={inputReceive}
                    onFocus={() => setChangeInput(TransferType.RECEIVE)}
                    onValueChange={setInputReceive}
                    decimalScale={decimaReceive}
                    decimalsLimit={decimaReceive}
                    formatValueOnBlur={false}
                />
            </div>
            <div className="flex flex-col items-end">
                <span className="text-secondary-foreground text-xs">
                    {t('received_you')}
                </span>
                <div
                    className="flex mt-1 cursor-pointer justify-between bg-card border h-9 text-sm items-center px-2 rounded-lg min-w-32"
                    onClick={() => setOpen(true)}
                >
                    <div className="flex items-center gap-x-1">
                        <Image
                            src={selectedReceive?.currency?.icon ?? DefaultIcon}
                            width={24}
                            height={24}
                            className="rounded-full w-6 h-6"
                            alt="coin"
                        />
                        <span className="pt-1">{selectedReceive?.currency.symbol}</span>
                    </div>
                    <ChevronDown size={20} />
                </div>
                <DialogCurrencies
                    open={open}
                    setOpen={setOpen}
                    selected={setSelectedReceive}
                />
            </div>
        </div>
    );
};
