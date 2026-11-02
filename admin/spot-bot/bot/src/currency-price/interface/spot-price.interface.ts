export interface ISpotPrice {
  symbol: string;
  last: number;
  changePercent: number;
  high24h: number;
  low24h: number;
  volume: number;
  quoteVolume: number;
  timestamp: number;
}