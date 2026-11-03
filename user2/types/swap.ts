export interface Config {
    swap_fee: number;
    swap_fee_max: number;
    swap_increase_to_currency_percentage: number;
    swap_decrease_from_currency_percentage: number;
    swap_max_increase_to_currency_amount: number;
    swap_max_decrease_from_currency_amount: number;
    swap_min_amount: number;
    swap_method: "inner" | 'international'
}

export enum TransferType {
    PAYMENT = 'payment',
    RECEIVE = 'receive',
    NULL = '',
}
export interface swapRow {
    id: number;
    from_currency_symbol: string;
    to_currency_symbol: string;
    from_amount: string;
    to_amount: string;
    to_final: string;
    fee: string;
    to_total: string;
    created_at: string;
    status: string;
}

export type StatusKey =
    | 'accepted_by_site'
    | 'accepted_by_supplier'
    | 'waiting'
    | 'waiting_by_supplier'
    | 'rejected_by_site'
    | 'rejected_by_supplier';
export interface StatusConfigItem {
    variant: 'success' | 'warning' | 'destructive';
    title: string;
}

export interface Currency {
    id: number;
    currency_id: number;
    symbol: string;
    name: string;
    icon: string;
    balance: string;
    price: number;
    rank: number;
    decimal: number;
    min_amount: string;
}
export interface SwapCurrency {
    changePercent: number;
    high24h: number;
    last: number;
    low24h: number;
    symbol: string;
    currency: Currency;
    currency_id?: number;
    buy_last?: number;
    sell_last?: number;
}
