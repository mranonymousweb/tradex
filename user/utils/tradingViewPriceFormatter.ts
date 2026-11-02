const getDecimalPlacesFromPriceScale = (pricescale: number): number => {
    if (!pricescale || pricescale <= 1) return 0;
    return Math.round(Math.log10(pricescale));
};

export const formatTradingViewPrice = (
    price: number,
    maxDecimals: number,
    signPositive?: boolean,
): string => {
    if (!Number.isFinite(price)) return String(price);

    const sign = price < 0 ? '-' : signPositive && price > 0 ? '+' : '';
    const absPrice = Math.abs(price);

    if (maxDecimals <= 0) {
        return sign + String(Math.round(absPrice));
    }

    const fixed = absPrice.toFixed(maxDecimals);
    const trimmed = fixed.replace(/(\.\d*?[1-9])0+$|\.0+$/g, '$1');

    return sign + trimmed;
};

export const createTradingViewCustomFormatters = () => ({
    priceFormatterFactory: (symbolInfo: { pricescale?: number; fractional?: boolean } | null) => {
        if (!symbolInfo || symbolInfo.fractional) return null;

        const maxDecimals = getDecimalPlacesFromPriceScale(symbolInfo.pricescale ?? 100);

        return {
            format: (price: number, signPositive?: boolean) =>
                formatTradingViewPrice(price, maxDecimals, signPositive),
        };
    },
    studyFormatterFactory: (
        format: { type?: string } | null,
        symbolInfo: { pricescale?: number } | null,
    ) => {
        if (format?.type !== 'price') return null;

        const maxDecimals = getDecimalPlacesFromPriceScale(symbolInfo?.pricescale ?? 100);

        return {
            format: (value: number) => formatTradingViewPrice(value, maxDecimals),
        };
    },
});
