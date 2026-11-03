import { MarketConfig } from '@/types/futures';

export const CalcLiqPrice = (
    inputPrice: string | undefined,
    inputSize: string | undefined,
    balance: number,
    config: MarketConfig,
) => {
    const MIN = 0.001;
    const leverage = config?.option?.futures_leverage;
    const marginMode = config?.option?.futures_margin_mode;
    let size = Number(inputSize);
    let entryPrice = Number(inputPrice);

    if (inputPrice === '' || inputSize === '') {
        return { long: 0, short: 0 };
    }

    // Simulating removeSpilter (you can adjust as needed)
    const removeSpilter = (val: string) => parseFloat(val);

    entryPrice = removeSpilter(String(entryPrice));
    size = Math.max(size, MIN);
    const accountBalance = Math.max(balance, MIN);

    const positionSize = entryPrice * leverage;
    const margin = Math.max(entryPrice * size * leverage, MIN);

    const longRate = config?.config?.long?.[`leverage_${leverage}`] || MIN;
    const shortRate = config?.config?.short?.[`leverage_${leverage}`] || MIN;
    const crossLongRate = config?.config?.long?.crossInterestRate || 0.0;
    const crossShortRate = config?.config?.short?.crossInterestRate || 0.0;

    // Replace with your constants
    const MARGIN_MODE_CROSS = 1;
    let LIQ_LONG = 0;
    let LIQ_SHORT = 0;

    // CROSS Margin
    if (marginMode === MARGIN_MODE_CROSS) {
        const adjustedBalance = accountBalance / margin;

        //Liq Long
        const denLong = adjustedBalance + crossLongRate;
        LIQ_LONG = denLong > MIN ? entryPrice / denLong : 0.0;

        //Liq Short
        const denShort = +adjustedBalance - crossShortRate;
        LIQ_SHORT = Math.abs(entryPrice / denShort);
        return { long: LIQ_LONG, short: LIQ_SHORT };
    }

    // ISOLATED (default)
    //Liq Long
    LIQ_LONG = longRate > MIN ? Math.abs(positionSize / longRate) : 0.0;
    //Liq Short
    LIQ_SHORT = shortRate > MIN ? Math.abs(positionSize / shortRate) : 0.0;
    return { long: LIQ_LONG, short: LIQ_SHORT };
};
