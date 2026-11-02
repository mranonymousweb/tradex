// gateio.exchange.prod.ts
import { Injectable, Logger } from '@nestjs/common';
import { RedisService } from 'src/redis/redis.service';
import WebSocket from 'ws';
import { ProviderBase } from './base.provider';
import { CurrencyService } from 'src/currency/currency.service';
import { SubscriptionRegistry } from '../SubscriptionRegistry.service';
import { SpotTickerConverterService } from '../spot-ticker-converter.service';

@Injectable()
export class GateioProvider extends ProviderBase {
  readonly name = 'gate_io';
  private logger = new Logger(GateioProvider.name);
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

  constructor(
    private redisService: RedisService,
    private readonly currencyService: CurrencyService,
    private readonly roomRegistry: SubscriptionRegistry,
    private readonly spotTickerConverterService: SpotTickerConverterService,

  ) {
    super();
  }

  protected getSocketUrl(): string {
    return 'wss://api.gateio.ws/ws/v4/';

  }

  // ---------------- CONNECT ----------------
  async connect(): Promise<void> {
            console.log("gate io try to connect")

    if (this.socket && this.socket.readyState === WebSocket.OPEN) return;


    this.socket = new WebSocket(this.getSocketUrl());

    this.socket.on('open', async () => {
      this.logger.log('[gateio] connected');
      this.isConnected = true;
      this.isReconnecting = false;
      this.reconnectDelay = 5000;
      this.startHeartbeat();

      await this.onOpen();
    });

    this.socket.on('message', async (data: WebSocket.Data) => {
      try {
        const parsed = JSON.parse(data.toString());
        await this.onMessage(parsed);
      } catch (err: any) {
        this.logger.error('[gateio] parse message error', err?.message || JSON.stringify(err));
      }
    });

    this.socket.on('close', (code: number) => {
      this.logger.warn(`[gateio] disconnected, code=${code}`);
      this.isConnected = false;
      this.stopHeartbeat();
      this.scheduleReconnect();
    });

    this.socket.on('error', (err: any) => {
      const msg = err?.message || err?.reason || JSON.stringify(err);
      this.logger.error('[gateio] ws error', msg);
      this.stopHeartbeat();
      this.scheduleReconnect();
    });
  }

  // ---------------- DISCONNECT ----------------
  async disconnect(): Promise<void> {
    this.shouldReconnect = false; // اضافه شود
    this.stopHeartbeat();
    if (!this.socket) return;

    this.logger.log('[gateio] force close');
    this.socket.terminate();
    this.socket = null;
    this.isConnected = false;
    this.isReconnecting = false;
  }

  // ---------------- RECONNECT ----------------
  private scheduleReconnect() {
    if (!this.shouldReconnect) return; // اضافه شود
    if (this.isReconnecting) return;
    this.isReconnecting = true;

    this.logger.warn(`[gateio] reconnecting in ${this.reconnectDelay} ms`);

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
        this.logger.error('[gateio] reconnect failed', JSON.stringify(err));
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
        this.logger.warn('[gateio] heartbeat failed, reconnecting');
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
    this.logger.log('[gateio] onOpen');
    const gatePairs = await this.fetchGatePairs();
    // const dbMarkets = await this.marketCurrencyService.getActiveMarkets();
    const dbMarkets = await this.currencyService.getActiveCurrencies();
    // console.log("dbMarkets", dbMarkets)

    const validPairs = [
      ...new Set(
        dbMarkets
          .map(m => m.pairs?.trim().toUpperCase())
          .filter(p => p && gatePairs.has(p)),
      ),
    ];

    if (!validPairs.length) {
      console.warn('[gateio] No valid pairs found to subscribe');
      return;
    }

    this.marketMap.clear();

    for (const pair of validPairs) {
      const market = dbMarkets.find(m => m.pairs.toUpperCase() === pair);
      if (market) this.marketMap.set(pair, market);
    }

    if (this.socket?.readyState !== WebSocket.OPEN) {
      this.logger.warn('[gateio] socket not open yet');
      return;
    }

    this.socket.send(
      JSON.stringify({
        time: Math.floor(Date.now() / 1000),
        event: 'subscribe',
        channel: 'spot.tickers',
        payload: validPairs,
      }),
    );
  }

  // ---------------- ON MESSAGE ----------------
  protected async onMessage(data: any) {
    if (!data) return;

    const promises: Promise<any>[] = [];

    if (data.event === 'ping') {
      this.socket?.send(JSON.stringify({ event: 'pong' }));
      return;
    }

    if (data.event === 'error') {
      this.logger.error('[gateio] error', JSON.stringify(data));
      return;
    }

    if (data.event === 'update') {
      const t = data.result;
      // console.log("gate io t is : ", t)
      const now = Date.now();

      const [crypto, base_crypto] = t.currency_pair.split('_');

      const enrichedTicker = {
        changePercent: t.change_percentage,
        high24h: t.high_24h,
        last: t.last,
        low24h: t.low_24h,
        quoteVolume: t.quote_volume,
        symbol: t.currency_pair,
        volume: t.base_volume,
        timestamp: now,
      };

      // fghf
      const last = this.lastUpdate.get(t.currency_pair) || 0;
      if (now - last < 500) return;
      this.lastUpdate.set(t.currency_pair, now);

      promises.push(
        this.redisService.setPrice(enrichedTicker.symbol, JSON.stringify(enrichedTicker)),
        this.handleTicker(enrichedTicker).catch(console.error),
        this.spotTickerConverterService
          .convertUsdtTickerToActiveBases(enrichedTicker, crypto, (ticker) =>
            this.handleTicker(ticker).catch(console.error),
          )
          .catch(console.error),
      );
    }

    await Promise.all(promises);
  }

  // ---------------- HANDLE TICKER ----------------
  private async handleTicker(ticker: any) {
    const now = Date.now();
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

  // ---------------- FETCH GATE PAIRS ----------------
  private async fetchGatePairs(): Promise<Set<string>> {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 10000);

    try {
      const response = await fetch('https://api.gateio.ws/api/v4/spot/currency_pairs', { signal: controller.signal });
      if (!response.ok) return new Set();

      const data = await response.json();
      const validPairs = data.filter((m: any) => m.trade_status === 'tradable').map((m: any) => m.id.toUpperCase());
      return new Set(validPairs);
    } catch (err: any) {
      console.error('[gateio] fetchGatePairs error:', err?.message || JSON.stringify(err));
      // this.fetchGatePairs()
      return new Set();
    } finally {
      clearTimeout(timeout);
    }
  }
}