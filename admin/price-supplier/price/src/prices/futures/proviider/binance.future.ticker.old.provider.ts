import { Injectable, Logger } from '@nestjs/common';
import { ProviderBase } from './base.provider';
import { RedisService } from 'src/redis/redis.service';
import WebSocket from 'ws';
import { CurrencyService } from 'src/currency/currency.service';
import { MarketCurrencyService } from 'src/market_currency/market_currency.service';
import { SubscriptionRegistryFuture } from './../SubscriptionRegistry.future.service';

@Injectable()
export class BinanceFuturesTickerProviderOld extends ProviderBase {
  readonly name = 'binance-ticker';
  private logger = new Logger(BinanceFuturesTickerProviderOld.name);

  private socket: WebSocket;
  private reconnectDelay = 5000;
  private symbols: string[] = [];
  private readonly CHUNK_SIZE = 50;
  private sockets: WebSocket[] = [];
  private ws: WebSocket;
private validPairs = new Set<string>();
private reconnectMap = new Map<string, number>();
  constructor(
    private redisService: RedisService,
    private readonly currencyService: CurrencyService,
    private readonly marketCurrencyService: MarketCurrencyService,
        private readonly roomRegistry: SubscriptionRegistryFuture,

  ) {
    super();
  }


async connect(): Promise<void> {
  if (this.isConnected || this.isConnecting) return;
  this.isConnecting = true;

  // const dbMarkets = await this.marketCurrencyService.getActiveFutureMarkets();
  const dbMarkets = [{pairs: "BTC_USDT"}]
  const allowedPairs = await this.getValidPairsFromBinance();

  const validPairs = new Set(
    dbMarkets
      .map(m => m.pairs?.trim().toLowerCase().replace('_', ''))
      .filter(p => p && allowedPairs.has(p))
  );

  if (!validPairs.size) {
    this.logger.warn('⚠️ No valid Binance pairs found');
    this.isConnecting = false;
    return;
  }

  // 👇 مهم: فقط 1 stream برای همه دیتا
  const ws = new WebSocket('wss://fstream.binance.com/ws/!ticker@arr');

  this.validPairs = validPairs
  this.ws = ws;

  ws.on('open', () => {
    this.logger.log(`✅ Binance WS connected (ALL tickers)`);
    this.isConnected = true;
    this.isConnecting = false;
  });

  ws.on('message', (data) => {
    console.log("message is ")
    try {
      const parsed = JSON.parse(data.toString());

      // Binance sends ARRAY for !ticker@arr
      if (Array.isArray(parsed)) {
        for (const item of parsed) {
          this.onMessage(item);
        }
      }
    } catch (err) {
      this.logger.error('Parse error: ' + err);
    }
  });

  ws.on('close', () => {
    this.logger.warn('🔌 WS closed, reconnecting...');
    this.reconnect();
  });

  ws.on('error', (err) => {
    this.logger.error('WS error: ' + err.message);
    ws.terminate();
    this.reconnect();
  });
}
async disconnect(): Promise<void> {
  this.isConnected = false;

  for (const ws of this.sockets) {
    ws.terminate();
  }

  this.sockets = [];
}

protected async onMessage(data: any) {
  const symbol = data.s?.toLowerCase();

  if (!symbol || !this.validPairs.has(symbol)) return;

  console.log('📡 ticker:', symbol);

  const { base, quote } = this.splitSymbol(symbol);
  const pair = `${base}_${quote}`;

  const ticker = {
    symbol: pair,
    price: parseFloat(data.c),
    change: parseFloat(data.p),
    changePercent: parseFloat(data.P),
    high: parseFloat(data.h),
    low: parseFloat(data.l),
    volume: parseFloat(data.v),
    quoteVolume: parseFloat(data.q),
    eventTime: data.E,
  };

  const existing = await this.redisService.client.hget('future-price', pair);
  const existingData = existing ? JSON.parse(existing) : {};

  const updated = {
    ...existingData,
    ...ticker,
    timestamp: Date.now(),
  };

  await this.redisService.futureSetPrice(pair, JSON.stringify(updated));

  this.handleTicker(updated).catch(console.error);
}


    private async handleTicker(ticker: any) {
    const now = Date.now();
    const parts = ticker.symbol.split('_');
    const baseCurrency = parts[1];

    console.log("before future taker : ",  ticker.symbol)
    if (this.roomRegistry.has("future:pair:price:" + ticker.symbol)) {
      console.log("after future taker : ",  ticker.symbol)
       await this.redisService.publish(
        'future:pair:price',
        JSON.stringify({
          pair: ticker.symbol,
          data: ticker,
          ts: now,
        }),
      );
    }
        
   
  }
  private async getValidPairsFromBinance(): Promise<Set<string>> {
    const res = await fetch('https://fapi.binance.com/fapi/v1/exchangeInfo');
    const data = await res.json();

    const symbols = data.symbols
      .filter((s: any) => s.status === 'TRADING') // فقط فعال‌ها
      .map((s: any) => s.symbol.toLowerCase());  // مثل btcusdt

    return new Set(symbols);
  }



private reconnect() {
  this.isConnected = false;

  setTimeout(() => {
    this.connect();
  }, this.reconnectDelay);

  this.reconnectDelay = Math.min(this.reconnectDelay * 2, 30000);
}
}