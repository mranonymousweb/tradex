import { UserPackageLevel } from '@/types/spot';

interface Props {
    price: number;
    amount: number;
    hasFiat: boolean;
    userPackage: UserPackageLevel | undefined;
    USD_IRR: number | undefined;
    side?: 1 | 2;
}

export function calculateOrder({
    price,
    amount,
    hasFiat,
    userPackage,
    USD_IRR,
    side = 1,
}: Props) {
    const isBuy = side === 1;

    const fee_percent_currency = Number(
        isBuy
            ? (userPackage?.currency_taker_fee ?? 0)
            : (userPackage?.currency_maker_fee ?? 0),
    );
    const fee_percent_fiat = Number(
        isBuy ? userPackage?.fiat_taker_fee : userPackage?.fiat_maker_fee,
    );
    const maxFeeCurrency = Number(
        isBuy
            ? userPackage?.currency_taker_fee_max
            : userPackage?.currency_maker_fee_max,
    );
    const maxFeeFiat = Number(
        isBuy
            ? userPackage?.fiat_taker_fee_max
            : userPackage?.fiat_maker_fee_max,
    );

    const feePercent = hasFiat ? fee_percent_fiat : fee_percent_currency;

    if (!price || !amount) {
        return {
            total: 0,
            received: 0,
            feeAmount: `${feePercent}%`,
            label: '0',
        };
    }

    const maxFee = hasFiat ? maxFeeFiat : maxFeeCurrency;
    const total = amount * price;
    const PRICE_USD_IRT = USD_IRR ?? 0;

    const feeBase =
        (amount * (feePercent / 100) * price) / (hasFiat ? PRICE_USD_IRT : 1);

    const cappedFeeBase = Math.min(feeBase, maxFee);
    const fee = hasFiat ? cappedFeeBase * PRICE_USD_IRT : cappedFeeBase;
    const received = amount - fee / price;
    //const feeAmount = feeBase > maxFee ? maxFee : `${feePercent}%`;
    const feeAmount =  `${feePercent ? feePercent : 0}%`;
    const label = feeBase > maxFee ? `${maxFee} USDT` : `${feePercent}%`;

    return { total, received, feeAmount, label };
}
