// gateio.exchange.prod.ts
import { Injectable, Logger } from '@nestjs/common';
import { MarketCurrencyService } from 'src/market_currency/market_currency.service';
import { RedisService } from 'src/redis/redis.service';
import WebSocket from 'ws';
import { ProviderBase } from './base.provider';
import { CurrencyService } from 'src/currency/currency.service';
import { SubscriptionRegistry } from '../SubscriptionRegistry.service';
import { io } from "socket.io-client";




@Injectable()
export class AriaGostaranProvider extends ProviderBase {
  readonly name = 'ariagostaran';

  private logger = new Logger(AriaGostaranProvider.name);

  private socket: WebSocket;
  private reconnectDelay = 5000;
  private maxReconnectDelay = 30000;
  private isReconnecting = false;
  private shouldReconnect = true;

  private lastUpdate = new Map<string, number>();


  constructor(
    private readonly marketCurrencyService: MarketCurrencyService,
    private redisService: RedisService,
    private readonly currencyService: CurrencyService,
    private readonly roomRegistry: SubscriptionRegistry,

  ) {
    super();
  }

  protected getSocketUrl(): string {
    // 👇 فقط relay
    return this.getRelayUrl();
  }

  // ---------------- CONNECT ----------------
  async connect(): Promise<void> {
    if (this.socket && this.socket.readyState === WebSocket.OPEN) return;

    console.log(this.getSocketUrl())
    this.socket = new WebSocket(this.getSocketUrl());

    this.socket.on('open', () => {
      this.logger.log('[ariagostaran] connected to relay');

      this.reconnectDelay = 5000;

      // 👇 subscribe به relay (نه gateio مستقیم)
      this.socket.send(JSON.stringify({
        type: 'subscribe',
        exchange: 'gate_io',
        symbols: 'ALL', // یا لیست خاص
      }));
    });

    this.socket.on('message', (data: WebSocket.Data) => {
      try {
        const msg = JSON.parse(data.toString());


        // 👇 اینجا دیتا از relay میاد
        this.onMessage(msg.data);

      } catch (err) {
        this.logger.error('[ariagostaran] relay parse error', err);
      }
    });

    this.socket.on('close', () => {
      this.logger.warn('[ariagostaran] relay disconnected');
      this.scheduleReconnect();
    });

    this.socket.on('error', (err) => {
      this.logger.error('[ariagostaran] relay error', err.message);
      this.scheduleReconnect();
    });
  }

  // ---------------- DISCONNECT ----------------
  async disconnect(): Promise<void> {
    this.shouldReconnect = false;

    if (this.socket) {
      this.socket.close();
      this.socket = null;
    }
  }

  // ---------------- RECONNECT ----------------
  private scheduleReconnect() {
    if (!this.shouldReconnect || this.isReconnecting) return;

    this.isReconnecting = true;

    setTimeout(() => {
      this.connect()
        .finally(() => {
          this.isReconnecting = false;
          this.reconnectDelay = Math.min(this.reconnectDelay * 2, this.maxReconnectDelay);
        });
    }, this.reconnectDelay);
  }

  // ---------------- ON MESSAGE ----------------
  protected async onMessage(data: any) {
    if (!data) return;

    const promises: Promise<any>[] = [];

    data = JSON.parse(data.toString());

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

      const last = this.lastUpdate.get(t.currency_pair) || 0;
      if (now - last < 500) return;
      this.lastUpdate.set(t.currency_pair, now);

        promises.push(
          // this.redisService.setPrice(`${enrichedTicker.symbol}`, JSON.stringify(enrichedTicker)),
          this.handleTicker(enrichedTicker).catch(console.error)
        )
       

      // const USDT2IRTConvetor = await this.redisService.getUSDT2IRTConvetor();
      // if (USDT2IRTConvetor) {
      //   const enrichedTickerIRT = {
      //     changePercent: t.change_percentage,
      //     high24h: t.high_24h * USDT2IRTConvetor.last,
      //     last: t.last * USDT2IRTConvetor.last,
      //     low24h: t.low_24h * USDT2IRTConvetor.last,
      //     quoteVolume: t.quote_volume * USDT2IRTConvetor.last,
      //     symbol: `${crypto}_IRT`,
      //     volume: t.base_volume * USDT2IRTConvetor.last,
      //     timestamp: now,
      //   };
      //   promises.push(
      //     this.redisService.setPrice(`${crypto}_IRT`, JSON.stringify(enrichedTickerIRT)),
      //     this.handleTicker(enrichedTickerIRT).catch(console.error)
      //   )
         
      // }
    }
  }

  // ---------------- HANDLE TICKER ----------------
  private async handleTicker(ticker: any) {
    const now = Date.now();
    const parts = ticker.symbol.split('_');
    const baseCurrency = parts[1];

          console.log("ticker ariagostaran", ticker)

    if (this.roomRegistry.has("pair:price:" + ticker.symbol)) {

      await this.redisService.publish(
        'pair:price',
        JSON.stringify({
          pair: ticker.symbol,
          data: ticker,
          ts: now,
        }),
      );
    }
     

  }
}