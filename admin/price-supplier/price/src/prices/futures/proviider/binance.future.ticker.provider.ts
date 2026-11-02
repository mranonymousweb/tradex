import { Injectable, Logger } from '@nestjs/common';
import { ProviderBase } from './base.provider';
import { RedisService } from 'src/redis/redis.service';
import WebSocket from 'ws';
import { CurrencyService } from 'src/currency/currency.service';
import { MarketCurrencyService } from 'src/market_currency/market_currency.service';

@Injectable()
export class BinanceFuturesTickerProvider extends ProviderBase {
  readonly name = 'binance-ticker';
  private logger = new Logger(BinanceFuturesTickerProvider.name);

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

 async connect(): Promise<void> {
  if (this.isConnected || this.isConnecting) return;
  this.isConnecting = true;

  const dbMarkets = await this.marketCurrencyService.getActiveFutureMarkets();
    // const dbMarkets = [{pairs: "BTC_USDT"}]
  // const validPairs = [
  //   ...new Set(
  //     dbMarkets
  //       .map(m => m.pairs?.trim().toLowerCase().replace('_', ''))
  //       .filter(Boolean)
  //   ),
  // ];

  const allowedPairs = await this.getValidPairsFromBinance();

  const validPairs =  Array.from( new Set(
    dbMarkets
      .map(m => m.pairs?.trim().toLowerCase().replace('_', ''))
      .filter(p => p && allowedPairs.has(p))
  ));
  this.logger.log(validPairs)

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
    const url = `wss://fstream.binance.com/stream?streams=${batch.map(s => `${s}@ticker`).join('/')}`;
    this.logger.log(url)
    const socket = new WebSocket(url);

    socket.on('open', () => {
      this.logger.log(`✅ Connected to Binance ticker WS (batch ${batch.join(',')})`);
    });

    socket.on('message', ((data) => {
      
      this.logger.log("on mesage", data.toString())
      // this.onMessage(JSON.parse(data.toString()))
    }))
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
    if (!data?.data) return;
     const now = Date.now()
    const symbol = data.data.s;
    const { base, quote } = this.splitSymbol(symbol);
    const pair = base + "_" + quote
    const ticker = {
      // changePercent: parseFloat(data.data.P),
      // last: parseFloat(data.data.c),
      // high24h: parseFloat(data.data.h),
      // low24h: parseFloat(data.data.l),
      // volume: parseFloat(data.data.v),
      // timestamp: now,
      // quoteVolume: parseFloat(data.data.Q),
      symbol: pair,

      price: parseFloat(data.data.c),
      change: parseFloat(data.data.p),
      changePercent: parseFloat(data.data.P),
      high: parseFloat(data.data.h),
      low: parseFloat(data.data.l),
      volume: parseFloat(data.data.v),
      quoteVolume: parseFloat(data.data.q),
      eventTime: data.data.E,
    };

        // 1. گرفتن رکورد فعلی از Redis
    const existingDataStr = await this.redisService.client.hget("future-price", pair);
    const existingData = existingDataStr ? JSON.parse(existingDataStr) : {};

    // 2. Merge کردن داده‌های جدید
    const updatedData = { ...existingData, ...ticker, timestamp: Date.now() };
    
    await this.redisService.futureSetPrice(`${ticker.symbol}`, JSON.stringify(updatedData));
    this.handleTicker(updatedData).catch(console.error);
  }

  private reconnect() {
    this.logger.log("on reconnect")
    if (this.socket) this.socket.terminate();
    this.isConnected = false;
    setTimeout(() => this.connect(), this.reconnectDelay);
    this.reconnectDelay = Math.min(this.reconnectDelay * 2, 30000);
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