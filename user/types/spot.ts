export interface UserPackageLevel {
  id: number;
  name: string;
  status: 'active' | 'inactive' | string;

  is_user_package: boolean;

  amount_to_grant: number;

  // currency fees
  currency_maker_fee: number;
  currency_taker_fee: number;
  currency_maker_fee_max: number;
  currency_taker_fee_max: number;

  // fiat fees
  fiat_maker_fee: string;
  fiat_taker_fee: string;
  fee_fiat: string;
  fiat_taker_fee_max: string;
  fiat_maker_fee_max: string;

  // limits
  currency_min_deposit: string;
  currency_min_withdrawal: string;

  deposit_min_fiat: string;
  deposit_max_fiat: string;

  withdrawal_min_fiat: string;
  withdrawal_max: number;

  // transfer
  internal_transfer_fee: string;

  // flags
  deposit: boolean;
  withdrawal: boolean;

  // extra
  marketing_profit: string;
}

export interface Currency {
  id: number;
  symbol: string;
  name: string;
  icon: string;
  type: 1 | 2 | 3;
}

export enum MarketStatus {
  Disabled = 0,
  Enabled = 1,
}

export interface TradePair {
  id: number;
  pairs: string;
  baseCurrency: Currency;
  purchasable: boolean;
  sellable: boolean;
  minimum_trade_amount: string;
  leverage: number;
  status: MarketStatus;
}

export interface MarketInfo {
  id: number;
  pairs: string;
  base_currency_id: number;
  main_currency_id: number;
  base_currency_symbol: string;
  main_currency_symbol: string;
  purchasable?: boolean;
  sellable?: boolean;
  minimum_trade_amount?: string;
  leverage?: number;
  status?: number;
}
export interface TcurrencyPriceSpot {
  symbol: string;
  last: string;
  high24h: string;
  low24h: string;
  volume: string;
  quoteVolume: string;
  changePercent: string;
}

export type TcurrencyFavoriteSpot = {
  id: number;
  mainCurrency: Currency;
  baseCurrency: Currency;
};

export type Order = {
  id: number;
  pairs: string;

  // enums are better than raw numbers
  side: 1 | 2; // 1 = buy, 2 = sell
  type: 1 | 2 | 3; // depends on backend (limit/market/stop...)

  amount: string;
  price: string;
  stop_price: string;
  market_price: string;

  total: string;
  filled_amount: string;
  filled_total: string;
  final_amount: string;
  fee: string;

  status: number;

  open_at: string; // ISO date
  done_at: string | null;
  canceled_at: string | null;
};

export type SpotOrders = {
  data: Order[];
  meta: {
    total: number;
    last_page: number;
  };
};
