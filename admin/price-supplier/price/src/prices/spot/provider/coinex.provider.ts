// gateio.exchange.prod.ts
import { Injectable, Logger } from '@nestjs/common';
import { RedisService } from 'src/redis/redis.service';
import WebSocket from 'ws';
import { ProviderBase } from './base.provider';
import { CurrencyService } from 'src/currency/currency.service';
import { SubscriptionRegistry } from './../SubscriptionRegistry.service';
import { SpotTickerConverterService } from '../spot-ticker-converter.service';

@Injectable()
export class CoinexProvider extends ProviderBase {
  readonly name = 'coinex';
  private logger = new Logger(CoinexProvider.name);
  private shouldReconnect = true;
  private socket: WebSocket;
  private lastUpdate = new Map<string, number>();
  private marketMap = new Map<string, any>();
  private lastSnapshot = 0;

  private reconnectDelay = 5000; // شروع delay
  private maxReconnectDelay = 30000;
  private isReconnecting = false;

  private heartbeatInterval = 15000;
  private heartbeatTimer: NodeJS.Timeout;


  // /////////////batching and changes socket call

  // private batchBuffer: any[] = [];
  // private batchTimer: NodeJS.Timeout;
  private batchInterval = 150; // latency کم + کنترل burst

  private lastPublished = new Map<string, { price: number; ts: number }>();

  private priceChangeThreshold = 0.001; // 0.1%
  private maxSilentTime = 5000; // fallback
  private roomCount = new Map<string, number>();

  // //////////////end of batching and changes socket call

  constructor(
    private redisService: RedisService,
    private readonly currencyService: CurrencyService,
    private readonly roomRegistry: SubscriptionRegistry,
    private readonly spotTickerConverterService: SpotTickerConverterService,
    
  ) {
    super();
  }

  protected getSocketUrl(): string {
    return 'wss://socket.coinex.com/';
  }

  // ---------------- CONNECT ----------------
  async connect(): Promise<void> {
        console.log("coinex try to connect")

    if (this.socket && this.socket.readyState === WebSocket.OPEN) return;

    this.socket = new WebSocket(this.getSocketUrl());

    this.socket.on('open', async () => {
      this.logger.log('[coinex] connected');
      this.isConnected = true;
      this.isReconnecting = false;
      this.reconnectDelay = 5000;
      this.startHeartbeat();

      await this.onOpen();
      // this.startBatchProcessor();
    });

    this.socket.on('message', async (data: WebSocket.Data) => {
      try {
        const parsed = JSON.parse(data.toString());
        await this.onMessage(parsed);
      } catch (err: any) {
        this.logger.error('[coinex] parse message error', err?.message || JSON.stringify(err));
      }
    });

    this.socket.on('close', (code: number) => {
      this.logger.warn(`[coinex] disconnected, code=${code}`);
      this.isConnected = false;
      this.stopHeartbeat();
      this.scheduleReconnect();
    });

    this.socket.on('error', (err: any) => {
      const msg = err?.message || err?.reason || JSON.stringify(err);
      this.logger.error('[coinex] ws error', msg);
      this.stopHeartbeat();
      this.scheduleReconnect();
    });
  }

  // ---------------- DISCONNECT ----------------
  async disconnect(): Promise<void> {
    this.shouldReconnect = false; // اضافه شود
    this.stopHeartbeat();
    if (!this.socket) return;

    this.logger.log('[coinex] force close');
    this.socket.terminate();
    this.socket = null;
    this.isConnected = false;
    this.isReconnecting = false;
    // this.stopBatchProcessor();
  }

  // ---------------- RECONNECT ----------------
  private scheduleReconnect() {
    if (!this.shouldReconnect) return; // اضافه شود
    if (this.isReconnecting) return;
    this.isReconnecting = true;

    this.logger.warn(`[coinex] reconnecting in ${this.reconnectDelay} ms`);

    setTimeout(async () => {
      try {
        if (this.socket) {
          this.socket.terminate();
          this.socket = null;
        }

        await this.connect();

        // exponential backoff
        this.reconnectDelay = Math.min(this.reconnectDelay * 2, this.maxReconnectDelay);
      } catch (err) {
        this.logger.error('[coinex] reconnect failed', JSON.stringify(err));
      } finally {
        this.isReconnecting = false;
      }
    }, this.reconnectDelay);
  }

  // ---------------- HEARTBEAT ----------------
  private startHeartbeat() {
    this.stopHeartbeat();
    this.heartbeatTimer = setInterval(() => {
      if (!this.socket || this.socket.readyState !== WebSocket.OPEN) return;
      try {
        this.socket.ping();
      } catch (err) {
        this.logger.warn('[coinex] heartbeat failed, reconnecting');
        this.scheduleReconnect();
      }
    }, this.heartbeatInterval);
  }

  private stopHeartbeat() {
    if (this.heartbeatTimer) {
      clearInterval(this.heartbeatTimer);
      this.heartbeatTimer = null;
    }
  }

  // ---------------- ON OPEN ----------------
  protected async onOpen() {
    this.logger.log('[coinex] onOpen');
    const coinexPairs = await this.fetchCoinExPairs();
    // const dbMarkets = await this.marketCurrencyService.getActiveMarkets();
    const dbMarkets = await this.currencyService.getActiveCurrencies();

    const validPairs = [
      ...new Set(
        dbMarkets
          .map(m => m.pairs?.trim().toUpperCase().replace('_', '')) // حذف آندرلاین
          .filter(p => p && coinexPairs.has(p))
      ),
    ];

    if (!validPairs.length) {
      console.warn('[coinex] No valid pairs found to subscribe');
      return;
    }

    this.marketMap.clear();

    for (const pair of validPairs) {
      const market = dbMarkets.find(m => m.pairs.toUpperCase() === pair);
      if (market) this.marketMap.set(pair, market);
    }

    if (this.socket?.readyState !== WebSocket.OPEN) {
      this.logger.warn('[coinex] socket not open yet');
      return;
    }

    this.socket.send(
      JSON.stringify({
        method: 'state.subscribe',
        params: validPairs,
        id: Date.now(),
      }),
    );
  }

  // ---------------- ON MESSAGE ----------------
  protected async onMessage(data: any) {
    if (!data) return;
    const promises: Promise<any>[] = [];

      // پاسخ به ping دریافتی از سرور
    if (data.method === 'server.ping') {
      this.socket?.send(JSON.stringify({ method: 'server.pong', id: data.id }));
      return;
    }

     // دریافت پاسخ pong به ping ارسالی ما – نیازی به اقدام خاصی نیست
    if (data.method === 'server.pong') {
      return;
    }

      // خطاهای سرور
    if (data.error) {
      this.logger.error(`[coinex] server error: ${data.error.code} - ${data.error.message}`);
      return;
    }

     // بروزرسانی ticker
    if (data.method === 'state.update') {
        const tickersObject = data.params[0];
        const now = Date.now();


        for (const [rawSymbol, tickerData] of Object.entries(tickersObject) as [string, any][]) {

          const last = this.lastUpdate.get(rawSymbol) || 0;
          if (now - last < 500) continue;

          this.lastUpdate.set(rawSymbol, now);

          let baseCurrency = '';

          if (rawSymbol.endsWith('USDT')) baseCurrency = 'USDT';
          else if (rawSymbol.endsWith('USDC')) baseCurrency = 'USDC';
          else if (rawSymbol.endsWith('BTC')) baseCurrency = 'BTC';
          else if (rawSymbol.endsWith('ETH')) baseCurrency = 'ETH';

          const crypto = baseCurrency
            ? rawSymbol.slice(0, -baseCurrency.length)
            : rawSymbol;

          const symbol = baseCurrency
            ? `${crypto}_${baseCurrency}`
            : rawSymbol;

          const enrichedTicker = {
            changePercent:
              ((tickerData.last - tickerData.open) / tickerData.open) * 100,
            high24h: tickerData.high,
            last: tickerData.last,
            low24h: tickerData.low,
            quoteVolume: tickerData.deal,
            symbol,
            volume: tickerData.volume,
            timestamp: now,
          };

          promises.push(
            this.redisService.setPrice(symbol, JSON.stringify(enrichedTicker)),
            this.handleTicker(enrichedTicker),
          );

          if (baseCurrency === 'USDT') {
            promises.push(
              this.spotTickerConverterService
                .convertUsdtTickerToActiveBases(enrichedTicker, crypto, (ticker) =>
                  this.handleTicker(ticker).catch(console.error),
                )
                .catch(console.error),
            );
          }
        }
      }

    await Promise.all(promises);
  }

  // ---------------- HANDLE TICKER ----------------
private async handleTicker(ticker: any) {
  const now = Date.now();

  const symbol = ticker.symbol;
  const base = symbol.split('_').pop();

  if (this.lastPublished.size > 2000) {
    const keys = this.lastPublished.keys();
    for (let i = 0; i < 500; i++) {
      const k = keys.next().value;
      if (!k) break;
      this.lastPublished.delete(k);
    }
  }
  // ❗ جلوگیری از spam
  const last = this.lastPublished.get(symbol);
  if (last && now - last.ts < 1000) return;

  this.lastPublished.set(symbol, {
    price: ticker.last,
    ts: now,
  });


    const parts = ticker.symbol.split('_');
    const baseCurrency = parts[1];


    // if (this.roomRegistry.has("pair:price:" + ticker.symbol)) {
        await this.redisService.publish(
          'pair:price',
          JSON.stringify({
            pair: ticker.symbol,
            data: ticker,
            ts: now,
          }),
        );
    // }
  
    //  this.redisService.publish(
    //   'base:price',
    //   JSON.stringify({
    //     pair: baseCurrency,
    //     data: ticker,
    //     ts: now,
    //   }),
    // );

    //   this.redisService.publish(
    //   'swap:base:price',
    //   JSON.stringify({
    //     pair: baseCurrency,
    //     data: ticker,
    //     ts: now,
    //   }),
    // );
}

  // private async handleTicker(ticker: any) {
  //   const now = Date.now();

  //   const symbol = ticker.symbol; // BTC_USDT
  //   const base = symbol.split('_').pop();
  //   const crypto = symbol.split('_')[0];

  //     // ❗ اگر هیچ سابسکرایبری نیست، اصلاً وارد pipeline نشه
  //   // if (!this.roomRegistry.has(symbol)) return;

  //   // ❗ dedup (کاهش 70-80٪ load)
  //   const last = this.lastPublished.get(symbol);
  //   if (
  //     last &&
  //     Math.abs(last.price - ticker.last) / last.price < this.priceChangeThreshold &&
  //     now - last.ts < this.maxSilentTime
  //   ) {
  //     return;
  //   }

  //   this.lastPublished.set(symbol, { price: ticker.last, ts: now });

  //   const enriched = {
  //     pair: symbol,
  //     base,
  //     data: {
  //       last: ticker.last,
  //       open: ticker.open,
  //       high: ticker.high,
  //       low: ticker.low,
  //       volume: ticker.volume,
  //       deal: ticker.deal
  //     },
  //     ts: now,
  //   };

  //   // ❗ جلوگیری از memory leak
  //   if (this.batchBuffer.length > 10000) {
  //     this.batchBuffer = this.batchBuffer.slice(-8000);
  //   }

  //   this.batchBuffer.push(enriched);
  // }

  // ---------------- FETCH COINEX PAIRS ----------------
  private async fetchCoinExPairs(): Promise<Set<string>> {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 10000);

    try {
      // استفاده از endpoint رسمی برای دریافت لیست بازارهای قابل معامله
      const response = await fetch('https://api.coinex.com/v1/market/list', { signal: controller.signal });
      
      if (!response.ok) return new Set();

      const json = await response.json();
      if (json.code !== 0) {
        this.logger.error(`[coinex] fetch pairs API error: ${json.message}`);
        return new Set();
      }

      const data = json.data;
     

      return new Set(data);
    } catch (err: any) {
      this.logger.error('[coinex] fetchCoinExPairs error:', err?.message || JSON.stringify(err));
      return new Set();
    } finally {
      clearTimeout(timeout);
    }
  }

  // batching functionsssssssssssssssssss
  // private startBatchProcessor() {
  //   if (this.batchTimer) return;

  //   this.batchTimer = setInterval(() => {
  //     if (!this.batchBuffer.length) return;

  //     const payload = this.batchBuffer.splice(0);

  //     // ========================
  //     // 1. pair:price (NO grouping)
  //     // ========================
  //   const filtered = payload.filter(item =>
  //     this.roomRegistry.has(item.pair)
  //   );

  //   if (filtered.length) {
  //     this.redisService.publish(
  //       'pair:price',
  //       JSON.stringify(filtered),
  //     );
  //   }

  //     // ========================
  //     // 2. base:price (grouped)
  //     // ========================
  //     const baseGrouped: Record<string, any[]> = {};

  //     for (const item of payload) {
  //       const base = item.base;

  //       if (!baseGrouped[base]) baseGrouped[base] = [];
  //       baseGrouped[base].push(item);
  //     }

  //     const basePayload = Object.entries(baseGrouped).map(([base, items]) => ({
  //       base,
  //       items,
  //       ts: Date.now(),
  //     }));

  //     if (basePayload.length) {
  //         this.redisService.publish(
  //           'base:price',
  //           JSON.stringify(basePayload),
  //         );

  //         // ========================
  //         // 3. swap:base:price (same structure)
  //         // ========================
  //         this.redisService.publish(
  //           'swap:base:price',
  //           JSON.stringify(basePayload),
  //         );
  //     }

    

  //   }, this.batchInterval);
  // }
  // private stopBatchProcessor() {
  //   if (this.batchTimer) {
  //     clearInterval(this.batchTimer);
  //     this.batchTimer = null;
  //   }
  // }
}