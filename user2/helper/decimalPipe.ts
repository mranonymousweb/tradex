// eslint-disable-next-line @typescript-eslint/no-explicit-any
//export const formatNumber = (value: any) => new Intl.NumberFormat().format(value)

//export const decimalPipe = (price?: number | string, deciaml = 4) => {
//  const numberPrice = sanitizeNumberInput(price ?? '');
//
//  if (numberPrice === 0) return "0";
//
//  const fixed = numberPrice.toFixed(Number(deciaml));
//  console.log("!!!", fixed)
//  const trimmed = fixed.replace(/(\.\d*?[1-9])0+$/g, '$1').replace(/\.0+$/, '');
//
//  const [intPart, decimalPart] = trimmed.split('.');
//  const formattedInt = formatNumber(intPart);
//  return decimalPart ? `${formattedInt}.${decimalPart}` : formattedInt;
//}
//function sanitizeNumberInput(input: string | number): number {
//  if (typeof input === 'number') return input;
//  let sanitized = input.replace(/[^0-9.]/g, '');
//  const parts = sanitized.split('.');
//  if (parts.length > 2) {
//    sanitized = parts[0] + '.' + parts.slice(1).join('');
//  }
//  const num = Number(sanitized);
//  return isNaN(num) ? 0 : num;
//}

const sanitizeNumberInput = (input: string | number) => {
    if (typeof input === 'number') return input;
    const sanitized = input.replace(/[^\d.]/g, ''); // keep digits & dot only
    const parts = sanitized.split('.');
    const normalized =
        parts.length > 2 ? parts[0] + '.' + parts.slice(1).join('') : sanitized;
    const num = parseFloat(normalized);
    return Number.isFinite(num) ? num : 0;
};

// Utility: format integer part with thousands separators
const formatNumber = (numStr: string) =>
    numStr.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

// Main: decimal pipe function
export const decimalPipe = (price: string | number, decimals = 4) => {
    const numberPrice = sanitizeNumberInput(price ?? '');
    if (!numberPrice) return '0';

    const factor = Math.pow(10, decimals);
    const truncated = Math.trunc(numberPrice * factor) / factor;

    const fixed = truncated.toFixed(decimals);
    // Trim unnecessary trailing zeros
    const trimmed = fixed.replace(/(\.\d*?[1-9])0+$|\.0+$/g, '$1');
    const [intPart, decimalPart] = trimmed.split('.');
    const formattedInt = formatNumber(intPart);
    return decimalPart ? `${formattedInt}.${decimalPart}` : formattedInt;
};

export const changePercentCoin = (value: string) => {
    return Number(value) > 0
        ? `+${Number(value).toFixed(2)}%`
        : `${Number(value).toFixed(2)}%`;
};

export const truncateNumber = (value: number, decimals: number) => {
    if (!Number.isFinite(value)) return '0';

    const factor = 10 ** decimals;
    const truncated = Math.trunc(value * factor) / factor;

    return truncated
        .toFixed(decimals)
        .replace(/(\.\d*?)0+$/, '$1')
        .replace(/\.$/, '');
};

export function handleDecimal(price: string | number, symbol?: string): string {
    const num = Number(price);

    if (symbol?.toLowerCase() === 'irt' && num >= 1) {
        return decimalPipe(num, 0);
    }

    if (num >= 1000) {
        return decimalPipe(num, 2);
    }

    if (num >= 1) {
        return decimalPipe(num, 4);
    }

    const str = num.toString();
    const dotIndex = str.indexOf('.');

    if (dotIndex === -1) {
        return decimalPipe(num, 2);
    }

    const decimalPart = str.slice(dotIndex + 1);

    let zeroCount = 0;

    for (const char of decimalPart) {
        if (char === '0') {
            zeroCount++;
        } else {
            break;
        }
    }

    return decimalPipe(num, zeroCount + 5);
}
