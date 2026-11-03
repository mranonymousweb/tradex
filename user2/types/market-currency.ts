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
export interface MarketCurrency {
  changePercent: number;
  high24h: number;
  icon: string;
  last: number;
  low24h: number;
  symbol: string;
  currency: Currency;
}

export interface MarketsRow {
  id: string;
  last: string;
  quoteVolume: number;
  volume: number;
  changePercent: number;
  currency: Currency;
}
