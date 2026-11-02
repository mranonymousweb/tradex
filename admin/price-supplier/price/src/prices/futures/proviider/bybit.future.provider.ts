import { Injectable, Logger } from '@nestjs/common';
import { ProviderBase } from './base.provider';
import { RedisService } from 'src/redis/redis.service';
import WebSocket from 'ws';
import { CurrencyService } from 'src/currency/currency.service';
import { MarketCurrencyService } from 'src/market_currency/market_currency.service';
import { SubscriptionRegistryFuture } from './../SubscriptionRegistry.future.service';

@Injectable()
export class BybitFuturesProvider extends ProviderBase {
  readonly name = 'bybit';
  private logger = new Logger(BybitFuturesProvider.name);

  private socket: WebSocket | null = null;
  private reconnectDelay = 5000;

  private state: Record<string, any> = {};
  private symbols: string[] = [];
  private lastPublished = new Map<string, { price: number; ts: number }>();

  constructor(
    private redisService: RedisService,
    private readonly currencyService: CurrencyService,
    private readonly marketCurrencyService: MarketCurrencyService,
    private readonly roomRegistry: SubscriptionRegistryFuture,
    
  ) {
    super();

    // optional: cleanup stale symbols (memory safety)
    setInterval(() => this.cleanupState(), 60000);
  }

  async connect(): Promise<void> {
    if (this.isConnected || this.isConnecting) return;
    this.isConnecting = true;

    const dbMarkets = await this.marketCurrencyService.getActiveFutureMarkets();
    const allowedPairs = await this.getValidPairsFromBybit();
 console.log(" dbMarkets", dbMarkets)
  console.log(" allowedPairs", allowedPairs)
    const validPairs = Array.from(
      new Set(
        dbMarkets
          .map(m => m.pairs?.trim().toLowerCase().replace(/_/g, ''))
          .filter(p => p && allowedPairs.has(p))
      )
    );
      console.log(" validPairs", validPairs)

    if (!validPairs.length) {
      this.logger.warn('⚠️ No valid pairs found');
      this.isConnecting = false;
      return;
    }

    this.symbols = validPairs;

    const ws = new WebSocket('wss://stream.bybit.com/v5/public/linear');

    ws.on('open', () => {
      this.logger.log('✅ Connected to Bybit');

      const args = this.symbols.map(s => `tickers.${s.toUpperCase()}`);

      ws.send(JSON.stringify({
        op: 'subscribe',
        args,
      }));

      // reset reconnect delay on success
      this.reconnectDelay = 5000;
    });

    ws.on('message', (raw) => {
      try {
        this.onMessage(JSON.parse(raw.toString()));
      } catch (e) {
        this.logger.error('Parse error: ' + e);
      }
    });

    ws.on('close', () => this.reconnect());
    ws.on('error', (err) => {
      this.logger.error('WS error: ' + err.message);
      this.reconnect();
    });

    this.socket = ws;

    this.isConnecting = false;
    this.isConnected = true;
  }

  async disconnect(): Promise<void> {
    this.isConnected = false;

    if (this.socket) {
      this.socket.removeAllListeners();
      this.socket.close();
      this.socket = null;
    }
  }

  // ---------------- MESSAGE HANDLER ----------------
  protected async onMessage(msg: any): Promise<void> {
    if (!msg?.topic?.startsWith('tickers.')) return;

    const symbol = msg.topic.split('.')[1];
    const d = msg.data;

    if (!this.state[symbol]) {
      this.state[symbol] = {};
    }

    // snapshot + delta merge
    Object.assign(this.state[symbol], d);
    this.state[symbol].__lastUpdate = Date.now();

    const s = this.state[symbol];

    // guard (VERY IMPORTANT)
    if (!s?.lastPrice || !s?.prevPrice24h) return;

    const last = Number(s.lastPrice);
    const prev = Number(s.prevPrice24h || s.lastPrice);

    const change = last - prev;
    const changePercent = prev ? (change / prev) * 100 : 0;

    const { base, quote } = this.splitSymbol(symbol);
    const pair = base + "_" + quote
    const now = Date.now();

    const ticker = {
      symbol: pair,

      price: last,
      high: Number(s.highPrice24h || 0),
      low: Number(s.lowPrice24h || 0),
      volume: Number(s.volume24h || 0),

      markPrice: Number(s.markPrice || 0),
      indexPrice: Number(s.indexPrice || 0),
      fundingRate: Number(s.fundingRate || 0),
      fundingTime: s.nextFundingTime || null,

      change,
      changePercent,
      quoteVolume: Number(s.turnover24h || 0),

      eventTime: msg.ts,
      timestamp: Date.now(),
    };

      // ❗ جلوگیری از spam
  const lastCheck = this.lastPublished.get(pair);
  if (lastCheck && now - lastCheck.ts < 300) return;

  this.lastPublished.set(pair, {
    price: ticker.price,
    ts: now,
  });
    await this.redisService.futureSetPrice(
      pair,
      JSON.stringify(ticker),
    );

    if (this.roomRegistry.has("future:pair:price:" + ticker.symbol)) {
      this.handleTicker(ticker).catch(console.error);
    }
    
  }

  // ---------------- HANDLE ----------------
  private async handleTicker(ticker: any) {
    await this.redisService.publish(
      'future:pair:price',
      JSON.stringify({
        pair: ticker.symbol,
        data: ticker,
        ts: Date.now(),
      }),
    );
  }

  // ---------------- RECONNECT ----------------
  private reconnect() {
    this.logger.log('🔁 reconnecting Bybit...');

    this.isConnected = false;
    this.isConnecting = false;

    if (this.socket) {
      this.socket.removeAllListeners();
      this.socket.close();
      this.socket = null;
    }

    // backoff increase
    this.reconnectDelay = Math.min(this.reconnectDelay * 2, 30000);

    setTimeout(() => {
      if (!this.isConnected && !this.isConnecting) {
        this.connect();
      }
    }, this.reconnectDelay);
  }

  // ---------------- CLEANUP STATE ----------------
  private cleanupState() {
    const now = Date.now();

    for (const symbol of Object.keys(this.state)) {
      if (now - this.state[symbol].__lastUpdate > 60000) {
        delete this.state[symbol];
      }
    }
  }

  // ---------------- PAIRS ----------------
  // private async getValidPairsFromBybit(): Promise<Set<string>> {
  //   const res = await fetch(
  //     'https://api.bybit.com/v5/market/instruments-info?category=linear'
  //   );

  //   const data = await res.json();

  //   const symbols = data.result.list
  //     .filter((s: any) => s.status === 'Trading')
  //     .map((s: any) => s.symbol.toLowerCase());

  //   return new Set(symbols);
  // }

  private async getValidPairsFromBybit(): Promise<Set<string>> {
    try {
      const controller = new AbortController();
      const timeout = setTimeout(() => controller.abort(), 15000); // 15s timeout

      const res = await fetch(
        'https://api.bybit.com/v5/market/instruments-info?category=linear',
        { signal: controller.signal }
      );

      clearTimeout(timeout);

      if (!res.ok) {
        this.logger.error(`❌ Bybit fetch failed: ${res.status} ${res.statusText}`);
        return new Set();
      }

      const data = await res.json();

      if (!data?.result?.list) {
        this.logger.error('❌ Bybit response format invalid');
        return new Set();
      }

      const symbols = data.result.list
        .filter((s: any) => s.status === 'Trading')
        .map((s: any) => s.symbol.toLowerCase());

      return new Set(symbols);

    } catch (err: any) {
      if (err.name === 'AbortError') {
        this.logger.error('⏳ Bybit fetch timeout (request aborted)');
      } else {
        this.logger.error('❌ Bybit fetch error: ' + err.message);
      }

      return new Set(); // جلوگیری از crash
    }
  }

}