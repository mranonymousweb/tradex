/**
 * Sample data for the landing page. Prices are illustrative only.
 * To show real prices, replace SAMPLE_MARKETS with data from
 * `getCurrencies()` (services/markets.ts) in page.tsx and pass it down.
 */
export type SampleMarket = {
  symbol: string;
  name: string;
  nameFa: string;
  price: number;
  change: number;
  hue: number;
  series: number[];
};

function mulberry32(seed: number) {
  let a = seed >>> 0;
  return () => {
    a = (a + 0x6d2b79f5) | 0;
    let t = Math.imul(a ^ (a >>> 15), 1 | a);
    t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}

export function makeSeries(seed: number, up: boolean, n = 24): number[] {
  const r = mulberry32(seed);
  const out: number[] = [];
  let v = 50;
  for (let i = 0; i < n; i++) {
    v += (r() - (up ? 0.42 : 0.58)) * 9;
    out.push(v);
  }
  return out;
}

const rows: Omit<SampleMarket, 'series'>[] = [
  { symbol: 'BTC', name: 'Bitcoin', nameFa: 'بیت‌کوین', price: 98420.5, change: 2.14, hue: 35 },
  { symbol: 'ETH', name: 'Ethereum', nameFa: 'اتریوم', price: 3412.8, change: 1.32, hue: 225 },
  { symbol: 'BNB', name: 'BNB', nameFa: 'بایننس‌کوین', price: 612.4, change: -0.58, hue: 46 },
  { symbol: 'SOL', name: 'Solana', nameFa: 'سولانا', price: 168.92, change: 4.71, hue: 270 },
  { symbol: 'XRP', name: 'XRP', nameFa: 'ریپل', price: 2.3145, change: -1.26, hue: 200 },
  { symbol: 'TON', name: 'Toncoin', nameFa: 'تون‌کوین', price: 5.842, change: 0.87, hue: 205 },
  { symbol: 'DOGE', name: 'Dogecoin', nameFa: 'دوج‌کوین', price: 0.1842, change: -2.03, hue: 42 },
];

export const SAMPLE_MARKETS: SampleMarket[] = rows.map((r, i) => ({
  ...r,
  series: makeSeries(101 + i * 17, r.change >= 0),
}));

export function fmtPrice(p: number): string {
  if (p >= 1000) return p.toLocaleString('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 2 });
  if (p >= 10) return p.toFixed(2);
  if (p >= 1) return p.toFixed(3);
  return p.toFixed(4);
}
