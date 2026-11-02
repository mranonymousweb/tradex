import { Injectable, Logger } from '@nestjs/common';
import { ProviderBase } from './base.provider';
import { RedisService } from 'src/redis/redis.service';
import WebSocket from 'ws';
import { CurrencyService } from 'src/currency/currency.service';
import { MarketCurrencyService } from 'src/market_currency/market_currency.service';

@Injectable()
export class GateFuturesTickerProvider extends ProviderBase {
  readonly name = 'gate-futures-ticker';

  private logger = new Logger(GateFuturesTickerProvider.name);

  private socket: WebSocket;

  private reconnectDelay = 5000;

  private symbols: string[] = [];

  constructor(
    private redisService: RedisService,
    private readonly currencyService: CurrencyService,
    private readonly marketCurrencyService: MarketCurrencyService,
  ) {
    super();
  }

  async connect(symbols: string[] = []): Promise<void> {
    if (this.isConnected || this.isConnecting) return;

    this.isConnecting = true;

  
    const dbMarkets = await this.marketCurrencyService.getActiveFutureMarkets();
    const allowedPairs = await this.getValidPairsFromGateIO();


    const validPairs =  Array.from( new Set(
      dbMarkets
        .map(m => m.pairs?.trim())
        .filter(p => p && allowedPairs.has(p))
    ));
    this.symbols = validPairs;

    this.socket = new WebSocket(
      'wss://fx-ws.gateio.ws/v4/ws/usdt',
    );

    this.socket.on('open', () => {
      this.logger.log('✅ Gate Ticker WS Connected');

      this.socket.send(
        JSON.stringify({
          time: Math.floor(Date.now() / 1000),

          channel: 'futures.tickers',

          event: 'subscribe',

          payload: this.symbols,
        }),
      );
    });

    this.socket.on('message', async (raw) => {
      try {
        const data = JSON.parse(raw.toString());
        await this.onMessage(data);
      } catch (e: any) {
        this.logger.error(e.message);
      }
    });

    this.socket.on('close', () => {
      this.logger.warn('❌ Gate Ticker WS Closed');
      this.reconnect();
    });

    this.socket.on('error', (err) => {
      this.logger.error(err.message);
      this.reconnect();
    });

    this.isConnected = true;
    this.isConnecting = false;
  }

  async disconnect(): Promise<void> {
    this.isConnected = false;

    if (this.socket) {
      this.socket.terminate();
    }
  }

  protected async onMessage(data: any): Promise<void> {
    if (data.channel !== 'futures.tickers') return;
    if (data.event !== 'update') return;

    const results = data.result;
     if (!Array.isArray(results)) return;

  for (const t of results) {
    const pair = t.contract; // BTC_USDT
    const now = Date.now()



    const ticker = {
      symbol: pair,

      price: parseFloat(t.last),
      change: parseFloat(t.change_price),
      changePercent: parseFloat(t.change_percentage),
      high: parseFloat(t.high_24h),
      low: parseFloat(t.low_24h),
      volume: parseFloat(t.volume_24h_base),
      quoteVolume: parseFloat(t.volume_24h_quote),
      eventTime: data.time_ms,
    };

        // 1. گرفتن رکورد فعلی از Redis
    const existingDataStr = await this.redisService.client.hget("future-price", pair);
    const existingData = existingDataStr ? JSON.parse(existingDataStr) : {};

    // 2. Merge کردن داده‌های جدید
    const updatedData = { ...existingData, ...ticker, timestamp: Date.now() };
    
    await this.redisService.futureSetPrice(`${ticker.symbol}`, JSON.stringify(updatedData));
    this.handleTicker(updatedData).catch(console.error);
  }


  }

      // ---------------- HANDLE TICKER ----------------
  private async handleTicker(ticker: any) {
    const now = Date.now();
    const parts = ticker.symbol.split('_');
    const baseCurrency = parts[1];

    await this.redisService.publish(
      'future:pair:price',
      JSON.stringify({
        pair: ticker.symbol,
        data: ticker,
        ts: now,
      }),
    );
    // await this.redisService.publish(
    //   'future:base:price',
    //   JSON.stringify({
    //     pair: baseCurrency,
    //     data: ticker,
    //     ts: now,
    //   }),
    // );
  }

  private calcChange(last, high, low) {
    const l = parseFloat(last);
    const h = parseFloat(high);
    const lo = parseFloat(low);

    if (!h || !lo) return 0;

    return ((l - lo) / lo) * 100;
  }

  private reconnect() {
    this.isConnected = false;

    if (this.socket) this.socket.terminate();

    setTimeout(() => {
      this.connect(this.symbols);
    }, this.reconnectDelay);

    this.reconnectDelay = Math.min(
      this.reconnectDelay * 2,
      30000,
    );
  }


    // get gateiofutures currency
  private async getValidPairsFromGateIO(): Promise<Set<string>> {
    try {
      const controller = new AbortController();
      const timeout = setTimeout(() => controller.abort(), 15000);

      const res = await fetch(
        'https://api.gateio.ws/api/v4/futures/usdt/contracts',
        {
          method: 'GET',
          headers: {
            Accept: 'application/json',
          },
          signal: controller.signal,
        },
      );

      clearTimeout(timeout);

      if (!res.ok) {
        this.logger.error(
          `❌ GateIO fetch failed: ${res.status} ${res.statusText}`,
        );
        return new Set();
      }

      const data = await res.json();

      if (!Array.isArray(data)) {
        this.logger.error('❌ GateIO response malformed');
        return new Set();
      }


      const result = data
        .filter((x: any) => !x.in_delisting)
        .map((x: any) => x.name);
      return new Set(result);

    } catch (err: any) {
      if (err.name === 'AbortError') {
        this.logger.error('⏳ GateIO fetch timeout (request aborted)');
      } else {
        this.logger.error('❌ GateIO fetch error: ' + err.message);
      }

      return new Set();
    }
  }
}