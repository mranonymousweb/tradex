// تعریف ثابت‌ها (به صورت آبجکت Readonly)
export const ASSET_TYPES = {
    CURRENCY: 'currency',
    FIAT: 'fiat',
} as const;

// استخراج تایپ از روی مقادیر ثابت‌ها
export type AssetType = typeof ASSET_TYPES[keyof typeof ASSET_TYPES]; 
// تایپ خروجی معادل است با: 'currency' | 'fiat' | 'crypto' | 'gold

export interface IOrderPayload {
    id: number;
    user_id?: number | null;
    main_currency_symbol?: string | null;
    base_currency_symbol?: string | null;
    pairs: string;
    side: number;
    type: number;
    leverage?: number;
    amount: string;
    price: string;
    avg_price: string;
    total: string;
    stop_price: string;
    market_price: string;
    filled_receive_amount: string;
    filled_amount: string;
    filled_total: string;
    fee: string;
    fee_percent: string;
    status: number;
    open_at?: string | null;
    done_at?: string | null;
    canceled_at?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    meta: any,
    maker?: object | null,
}