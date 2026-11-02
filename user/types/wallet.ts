export interface Network {
    id: number;
    symbol: string;
    icon: string;
    priority: number;
    is_withdraw_able: boolean;
    is_deposit_able: boolean;
    has_tag: boolean;
    status: number;
    fee: string;
    type: number;
    min_deposit: string | number;
    max_withdraw: string;
    min_withdraw: string;
    max_deposit: number;
}

export interface Currency {
    id: number;
    name: string;
    symbol: string;
    icon: string;
    decimal: number;
    networks: Network[] | any;
    type: number;
    payments: string[];
    cards: Cards[];
    gateway: Gateways[] | Record<string, string>;
    user_asset: {
        future: string;
        futurePercentage: number;
        spot: string;
        spotPercentage: number;
        total: number | string;
        blockPercentage: number;
        block: string;
    };
}

export interface Gateways {
    key: string;
    value: string;
}

export interface Cards {
    id: number;
    bank: BankInfo;
    card_number: string;
    card_owner: string;
    sheba: string;
}

export interface BankInfo {
    id: number;
    icon: string;
    name: string;
}
export interface Assets {
    spot: number;
    future: number;
    block: number;
    total: number;
    spotPercentage: number;
    futurePercentage: number;
    blockPercentage: number;
}
export interface BanksItems {
    label: string;
    info: string;
    copied: boolean;
}

export interface AssetsRow {
    id: string;
    symbol: string;
    name: string;
    balance: string;
    feature_balance: string;
    block_balance: string;
    margin_balance: string;
    icon: string;
    currency_id: number;
    total_balance: number;
}

export interface CurrencyConversion {
    id: string;
    symbol: string;
    name: string;
    balance: string;
    feature_balance: string;
    block_balance: string;
    margin_balance: string;
    icon: string;
}

export interface CurrencyConversionLogRow {
    id: number;
    user_id: number;
    currency_id: number;
    symbol: string;
    amount: string;
    price: string;
    total: string;
    fee: string;
    format: string;
    created_at: string; // ISO date string
    updated_at: string; // ISO date string
}

export enum Supplier {
    NOTSUPPLIER = 'special',
    HASSUPPLIER = 'non_special',
}

export enum TransactionStatus {
    Waiting = 1,
    Confirmed = 2,
    Reject = 3,
    WaitingBySupplier = 4,
    Fishing = 5,
    Fail = 6,
}
export interface WalletRecive {
    address: string;
    tag: string;
    supplier: Supplier;
}
