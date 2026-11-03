import { Config, SwapCurrency } from '@/types/swap';

export const calculateSwap = (
    config: Config,
    currencies: SwapCurrency[],
    inputAmount: string,
    selectedPayment: SwapCurrency,
    selectedReceive: SwapCurrency,
    side: 'received' | 'payment',
) => {
    const amount = Number(inputAmount);

    if (!Number.isFinite(amount) || amount <= 0) {
        return '';
    }

    const currencyMap = new Map(
        currencies.map((currency) => [
            currency.currency.id,
            currency,
        ]),
    );

    const paymentCurrency = currencyMap.get(selectedPayment.currency.id);
    const receiveCurrency = currencyMap.get(selectedReceive.currency.id);

    if (!paymentCurrency || !receiveCurrency) {
        return '';
    }

    const irtCurrency = currencies.find(
        (currency) => currency.currency.symbol === 'IRT',
    );

    if (!irtCurrency) {
        return '';
    }

    const isInternational = config.swap_method === 'international';
    const irtBuyPrice = Number(irtCurrency.buy_last);
    const irtSellPrice = Number(irtCurrency.sell_last);

    if (
        !isInternational &&
        (!Number.isFinite(irtBuyPrice) ||
            !Number.isFinite(irtSellPrice) ||
            irtBuyPrice <= 0 ||
            irtSellPrice <= 0)
    ) {
        return '';
    }

    const getUsdtPrice = (currency: SwapCurrency): number => {
        const symbol = currency.currency.symbol;

        if (symbol === 'USDT') {
            return 1;
        }
        if (symbol === 'IRT') {
            return 0;
        }

        const price = Number(currency.last);

        return Number.isFinite(price) && price > 0 ? price : 0;
    };

    const getPrice = (
        currency: SwapCurrency,
        type: 'payment' | 'receive',
    ): number => {
        if (isInternational) {
            const price = Number(currency.last);

            return Number.isFinite(price) && price > 0 ? price : 0;
        }

        const symbol = currency.currency.symbol;

        if (symbol === 'IRT') {
            return 1;
        }

        if (symbol === 'USDT') {
            return type === 'payment'
                ? irtBuyPrice
                : irtSellPrice;
        }

        const usdtPrice = getUsdtPrice(currency);

        if (!usdtPrice) {
            return 0;
        }

        const usdtToIrt =
            type === 'payment'
                ? irtBuyPrice
                : irtSellPrice;

        return usdtPrice * usdtToIrt;
    };

    const paymentPrice = getPrice(paymentCurrency, 'payment');
    const receivePrice = getPrice(receiveCurrency, 'receive');

    if (!paymentPrice || !receivePrice) {
        return '';
    }

    let adjustedPaymentPrice = paymentPrice;

    if (isInternational) {
        const percentageDecrease =
            paymentPrice *
            (Number(config.swap_decrease_from_currency_percentage ?? 0) / 100);

        const fixedDecrease = Number(
            config.swap_max_decrease_from_currency_amount ?? 0,
        );

        const adjustment = Math.min(
            percentageDecrease,
            fixedDecrease,
        );

        adjustedPaymentPrice =
            paymentPrice - adjustment;
    }

    if (adjustedPaymentPrice <= 0) {
        return '';
    }

    const result =
        side === 'payment'
            ? (amount * receivePrice) / adjustedPaymentPrice
            : (amount * adjustedPaymentPrice) / receivePrice;

    if (!Number.isFinite(result) || result < 0) {
        return '';
    }

    return String(result);
};
