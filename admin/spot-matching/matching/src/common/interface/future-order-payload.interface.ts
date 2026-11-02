export interface FutureIOrderPayload {
    id: number;
    user_id?: number | null;
    main_currency_symbol?: string | null;
    base_currency_symbol?: string | null;
    pairs: string;
    quantity: string;
    price: string;
    stop_price: string;
    enter_balance: string;
    has_tpsl: boolean | false;
    take_profit: string;
    stop_loss: string;
    enter_price: string;
    liq_price: string;
    fee: number | null;
    cost: number | null;
    side: number;
    type: number;
    leverage: number;
    margin_mode: number;
    status: number;
    open_at?: Date | null;
    pnl?: number | null;
    created_at?: Date | null;
    closed_at?: Date | null;
    liquidation_at: Date | null;
    exit_type: string | null,
    market_price?: string | null;
}