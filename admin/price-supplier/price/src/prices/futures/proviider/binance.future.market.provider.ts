import { Injectable, Logger } from '@nestjs/common';
import { ProviderBase } from './base.provider';
import { RedisService } from 'src/redis/redis.service';
import WebSocket from 'ws';
import { CurrencyService } from 'src/currency/currency.service';
import { MarketCurrencyService } from 'src/market_currency/market_currency.service';

@Injectable()
export class BinanceFuturesMarketProvider extends ProviderBase {
  readonly name = 'binance-market';
  private logger = new Logger(BinanceFuturesMarketProvider.name);

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
    this.symbols = symbols;

    const dbMarkets = await this.marketCurrencyService.getActiveFutureMarkets();
    // const validPairs = [
    //   ...new Set(
    //     dbMarkets
    //       .map(m => m.pairs?.trim().toLowerCase().replace('_', ''))
    //       .filter(Boolean)
    //   ),
    // ];


  const allowedPairs = await this.getValidPairsFromBinance();

  // const validPairs =  Array.from( new Set(
  //   dbMarkets
  //     .map(m => m.pairs?.trim().toLowerCase().replace('_', ''))
  //     .filter(p => p && allowedPairs.has(p))
  // ));

  const validPairs = ["btcusdt"]
  if (!validPairs.length) {
    this.logger.warn('⚠️ No valid Binance pairs found');
    this.isConnecting = false;
    return;
  }
    this.symbols = validPairs;

    const BATCH_SIZE = 50;
    const batches = [];
    for (let i = 0; i < this.symbols.length; i += BATCH_SIZE) {
      batches.push(this.symbols.slice(i, i + BATCH_SIZE));
    }

    batches.forEach(batch => {
        const url = `wss://fstream.binance.com/stream?streams=${batch.map(s => `${s}@markPrice`).join('/')}`;
        const socket = new WebSocket(url);
    
        socket.on('open', () => {
          this.logger.log(`✅ Connected to Binance ticker WS (batch ${batch.join(',')})`);
        });
    
        socket.on('message', (data) => this.onMessage(JSON.parse(data.toString())));
        socket.on('close', () => this.reconnect());
        socket.on('error', (err) => {
          this.logger.error('WS error: ' + err.message);
          this.reconnect();
        });
    });

    this.isConnecting = false;
    this.isConnected = true;
  }

  async disconnect(): Promise<void> {
    this.isConnected = false;
    if (this.socket) this.socket.terminate();
  }

  protected async onMessage(data: any): Promise<void> {
    console.log("onMessage : ", data)
    if (!data?.data) return;
    const now = Date.now()
    const symbol = data.data.s;
     const { base, quote } = this.splitSymbol(symbol);
    const pair = base + "_" + quote
    const market = {
      markPrice: parseFloat(data.data.p),
      indexPrice: parseFloat(data.data.i),
      fundingRate: parseFloat(data.data.r),
      fundingTime: data.data.T,
      timestamp: now,
      eventTime: data.data.E,                  // زمان دریافت قیمت
    };

     // 1. گرفتن رکورد فعلی از Redis
    const existingDataStr = await this.redisService.client.hget("future-price", pair);
    const existingData = existingDataStr ? JSON.parse(existingDataStr) : {};

    // 2. Merge کردن داده‌های جدید
    const updatedData = { ...existingData, ...market, timestamp: Date.now() };
     await this.redisService.futureSetPrice(pair, JSON.stringify(updatedData));
    // await this.redisService.setPrice(`binance:market:${symbol}`, JSON.stringify(updatedData));
  }

  private reconnect() {
    if (this.socket) this.socket.terminate();
    this.isConnected = false;
    setTimeout(() => this.connect(this.symbols), this.reconnectDelay);
    this.reconnectDelay = Math.min(this.reconnectDelay * 2, 30000);
  }

  //   private async getValidPairsFromBinance(): Promise<Set<string>> {
  //   const res = await fetch('https://fapi.binance.com/fapi/v1/exchangeInfo');
  //   const data = await res.json();

  //   const symbols = data.symbols
  //     .filter((s: any) => s.status === 'TRADING') // فقط فعال‌ها
  //     .map((s: any) => s.symbol.toLowerCase());  // مثل btcusdt

  //   return new Set(symbols);
  // }

  private async getValidPairsFromBinance(): Promise<Set<string>> {
    try {
      const controller = new AbortController();
      const timeout = setTimeout(() => controller.abort(), 15000); // timeout 15s

      const res = await fetch('https://fapi.binance.com/fapi/v1/exchangeInfo', { signal: controller.signal });

      clearTimeout(timeout);

      if (!res.ok) {
        this.logger.error(`❌ Binance fetch failed: ${res.status} ${res.statusText}`);
        return new Set();
      }

      const data = await res.json();

      if (!data?.symbols || !Array.isArray(data.symbols)) {
        this.logger.error('❌ Binance response malformed');
        return new Set();
      }

      const symbols = data.symbols
        .filter((s: any) => s.status === 'TRADING')
        .map((s: any) => s.symbol.toLowerCase());
      return new Set(symbols);

    } catch (err: any) {
      if (err.name === 'AbortError') {
        this.logger.error('⏳ Binance fetch timeout (request aborted)');
      } else {
        this.logger.error('❌ Binance fetch error: ' + err.message);
      }

      return new Set(); // جلوگیری از کرش در زمان خطا
    }
  }

}