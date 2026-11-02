export interface CurrencyStock {
  id: number;
  min_amount: string;
  max_amount: string;
  min_day: string;
  daily_profit_percent: string;
  balance: string;
  currency: Currency;
}

export interface StockRow {
  id: number;
  day: number;
  daily_profit_percent: string;
  status: StockStatus;
  created_at: string;
  amount: string;
  currency: Currency;
  total_profit: string;
  staking: {
    min_day: string;
  };
}

export interface Currency {
  id: number;
  symbol: string;
  name: string;
  icon: string;
}

export enum StockStatus {
  WATTING = 'waiting',
  ACTIVE = 'active',
  INACTIVE = 'inactive',
}
