export interface TcurrencyPrice {
    change: number;
    changePercent: number;
    eventTime: number;
    fundingRate: number;
    fundingTime: number;
    high: number;
    high24h: number;
    low24h: number;
    indexPrice: number;
    low: number;
    markPrice: number;
    pairs: string;
    price: number;
    quoteVolume: number;
    symbol: string;
    volume: number;
    pair?: TpairMarketBase;
    leverage: number
}

export interface TpairMarketBase {
    market_currency_id: number;
    pairs: string;
    status: number;
    is_favorite: boolean;
    max_leverage: number;
}

type LeverageLevels = {
    [K in `leverage_${number}`]: number;
};
export interface favoriteType {
    data: {
        market_currency_id: number;
    };
    url: string;
}

type PositionConfig = {
    crossInterestRate: number;
} & LeverageLevels;

export interface MarketConfig {
    option: {
        futures_leverage: number;
        futures_margin_mode: number;
    };
    settings: {
        future_periodic_profit: string,
        future_long_position_fee: string,
        future_short_position_fee: string,
        future_first_period: string,
        future_second_period: string,
        future_third_period: string,
        mata_data_future_rules_isolated: string,
        mata_data_future_rules_cross: string
    },
    config: {
        long: PositionConfig;
        short: PositionConfig;
    };
}

export interface AddOrderProps {
    main: string;
    base: string;
    quantity?: string;
    balance: number;
    hastpsl: boolean;
    takeProfit: string | undefined;
    stopLoss: string | undefined;
    enterPrice?: string;
    type: number;
    invalid: boolean;
}

export type Trade = {
    id: number;
    pairs: string;
    main_currency_symbol: string;
    base_currency_symbol: string;
    side: number; // 1 for Buy, 2 for Sell (assumption)
    type: 'Limit' | 'Market' | string;
    leverage: number;
    enter_price: string;
    exit_price: string | null;
    margin: string;
    pnl: number;
    roi: number;
    break_even: string;
    market_price: string;
    margin_ratio: number;
    liq_price: string;
    has_tp_sl: boolean | null;
    take_profit: string | null;
    stop_loss: string | null;
    margin_mode: 'cross' | 'isolated' | string;
    position_size: string;
    quantity: string;
    fee: string;
    opened_at: string;
    done_at: string | null;
    closed_at: string | null;
    liquidation_at: string | null;
};

export type TradeData = {
    data: Trade[];
    meta: {
        total: number;
        last_page: number;
    };
};

export type PositionSide = 1 | 2; // 1 = Long, 2 = Short

export type PositionStatus = 1 | 2 | 3; // Open, Closed, Liquidated

export type OrderType = 'Limit' | 'Market';

export type MarginMode = 'cross' | 'isolated';

export interface Position {
    id: number;
    pairs: string;
    main_currency_symbol: string;
    base_currency_symbol: string;
    side: PositionSide;
    type: OrderType;
    leverage: number;
    enter_price: string;
    exit_price: string | null;
    margin: number;
    pnl: number | null;
    roi: number;
    break_even: number;
    market_price: number;
    margin_ratio: number;
    liq_price: string;
    stop_price: string | null;
    has_tpsl: boolean;
    take_profit: string | null;
    stop_loss: string | null;
    margin_mode: MarginMode;
    position_size: number;
    quantity: string;
    fee: string;
    opened_at: string;
    done_at: string | null;
    closed_at: string | null;
    liquidation_at: string | null;
    status: PositionStatus;
}
