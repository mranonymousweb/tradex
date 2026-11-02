import { Injectable, Logger } from '@nestjs/common';
import { ProviderBase } from './base.provider';
import { RedisService } from 'src/redis/redis.service';
import WebSocket from 'ws';
import { CurrencyService } from 'src/currency/currency.service';
import { MarketCurrencyService } from 'src/market_currency/market_currency.service';

@Injectable()
export class GateFuturesMarketProvider extends ProviderBase {
  readonly name = 'gate-market';

  private logger = new Logger(GateFuturesMarketProvider.name);

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
      this.logger.log('✅ Connected to Gate.io Futures WS');

      // subscribe mark price updates
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
      this.logger.warn('❌ Gate WS Closed');
      this.reconnect();
    });

    this.socket.on('error', (err) => {
      this.logger.error('WS error: ' + err.message);
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
    if (
      data.event !== 'update' ||
      data.channel !== 'futures.tickers'
    ) {
      return;
    }

    const results = data.result;

 if (!Array.isArray(results)) return;

  for (const t of results) {
    const pair = t.contract;

    if (!pair) continue;

    const now = Date.now()
    //  const { base, quote } = this.splitSymbol(symbol);
    // const pair = base + "_" + quote
    const market = {
      markPrice: parseFloat(t.mark_price),
      indexPrice: parseFloat(t.index_price),
      fundingRate: parseFloat(t.funding_rate),
      fundingTime:  t.t,
      timestamp: now,
      eventTime: data.time_ms,                // زمان دریافت قیمت
    };

     // 1. گرفتن رکورد فعلی از Redis
    const existingDataStr = await this.redisService.client.hget("future-price", pair);
    const existingData = existingDataStr ? JSON.parse(existingDataStr) : {};

    // 2. Merge کردن داده‌های جدید
    const updatedData = { ...existingData, ...market, timestamp: Date.now() };
     await this.redisService.futureSetPrice(pair, JSON.stringify(updatedData));

    }

  }

  private reconnect() {
    this.isConnected = false;

    if (this.socket) {
      this.socket.terminate();
    }

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