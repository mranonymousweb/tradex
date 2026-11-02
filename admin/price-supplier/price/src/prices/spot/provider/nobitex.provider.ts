import { Injectable, Logger } from '@nestjs/common';
import { Centrifuge, Subscription } from 'centrifuge';
import { RedisService } from 'src/redis/redis.service';
import { ProviderBase } from './base.provider';
import { CurrencyService } from 'src/currency/currency.service';
import { SubscriptionRegistry } from './../SubscriptionRegistry.service';
import WebSocket from 'ws';

@Injectable()
export class NobitexProvider extends ProviderBase {
  readonly name = 'nobitex';
  private logger = new Logger(NobitexProvider.name);
  private shouldReconnect = true;

  private client: Centrifuge | null = null;
  private sub: Subscription | null = null;
  private reconnectTimer: NodeJS.Timeout | null = null;

  private reconnectDelay = 3000;
  private maxReconnectDelay = 30000;

  constructor(
    private readonly redisService: RedisService,
    private readonly currencyService: CurrencyService,
    private readonly roomRegistry: SubscriptionRegistry,
  ) {
    super();
  }

  protected getSocketUrl(): string {
    return 'wss://ws.nobitex.ir/connection/websocket';
  }

  async connect(): Promise<void> {
    this.logger.log('[nobitex] try to connect');

    if (this.client && this.client.state === 'connected') {
      return;
    }

    await this.cleanup();

    // this.client = new Centrifuge(this.getSocketUrl(), { debug: false });
    this.client = new Centrifuge(this.getSocketUrl(), {
      debug: false,
      websocket: WebSocket,
    });
    this.sub = await this.client.newSubscription('public:market-stats-all');

    await this.sub.on('publication', (ctx) => this.onMessage(ctx.data));

    await this.sub.on('subscribed', () => {
      this.logger.log('[nobitex] subscribed to public:market-stats-all');
    });

    await this.sub.on('error', (err) => {
      this.logger.error('[nobitex] subscription error', err);
    });

    await this.client.on('connected', () => {
      this.logger.log('[nobitex] connected');
      this.isConnected = true;
      this.reconnectDelay = 3000;
      this.clearReconnectTimer();

      try {
        this.sub?.subscribe();
      } catch (err) {
        this.logger.error('[nobitex] subscribe failed', err);
      }
    });

    this.client.on('disconnected', (ctx) => {
      this.logger.warn(`[nobitex] disconnected: ${ctx.reason} (code=${ctx.code})`);
      this.isConnected = false;

      if (this.shouldReconnect) {
        this.scheduleReconnect();
      }
    });

    this.client.on('error', (err) => {
      this.logger.error('[nobitex] ws error', err);
    });

    this.client.connect();
  }

  async disconnect(): Promise<void> {
    this.shouldReconnect = false;
    this.clearReconnectTimer();
    await this.cleanup();
    this.isConnected = false;
  }

  private scheduleReconnect() {
    if (!this.shouldReconnect) return;
    if (this.reconnectTimer) return;

    const delay = this.reconnectDelay;

    this.logger.warn(`[nobitex] reconnecting in ${delay} ms`);

    this.reconnectTimer = setTimeout(async () => {
      this.reconnectTimer = null;

      try {
        await this.connect();
      } catch (err) {
        this.logger.error('[nobitex] reconnect failed', err);
      }

      if (!this.isConnected && this.shouldReconnect) {
        this.reconnectDelay = Math.min(this.reconnectDelay * 2, this.maxReconnectDelay);
        this.scheduleReconnect();
      }
    }, delay);
  }

  private clearReconnectTimer() {
    if (this.reconnectTimer) {
      clearTimeout(this.reconnectTimer);
      this.reconnectTimer = null;
    }
  }

  private async cleanup() {
    if (this.sub) {
      try {
        this.sub.unsubscribe();
      } catch {}
      this.sub = null;
    }

    if (this.client) {
      try {
        this.client.disconnect();
      } catch {}
      this.client = null;
    }
  }

  protected async onMessage(data: any) {
    console.log("get new message from nobitex spot",)
    if (!data) return;

    const now = Date.now();

    for (const [pair, itemTemp] of Object.entries(data)) {
      const item = itemTemp as any;

      const key = this.calculateSymbol(pair)
        .replace(/-/g, '_')
        .toUpperCase();

      const [crypto, base_crypto] = key.split('_');
      let ticker;

      if (base_crypto === 'USDT') {
        ticker = {
          symbol: key,
          last: this.calculatePrice(pair, item.latest),
          high24h: this.calculatePrice(pair, item.dayHigh),
          low24h: this.calculatePrice(pair, item.dayLow),
          volume: item.volumeSrc,
          quoteVolume: item.volumeDst,
          changePercent: item.dayChange,
          timestamp: now,
        };
        await this.handleTicker(ticker).catch(console.error);
      } else if (base_crypto === 'RLS') {
        if (crypto === 'USDT') continue;
        // console.log("crypto", crypto)

        ticker = {
          symbol: `${crypto}_IRT`,
          last: this.calculatePrice(pair, item.latest) / 10,
          high24h: this.calculatePrice(pair, item.dayHigh) / 10,
          low24h: this.calculatePrice(pair, item.dayLow) / 10,
          volume: item.volumeSrc,
          quoteVolume: item.volumeDst,
          changePercent: item.dayChange,
          timestamp: now,
        };
        await this.handleTicker(ticker).catch(console.error);
      }
    }
  }

  private async handleTicker(ticker: any) {
    if (ticker.symbol === 'USDT_IRT') return;
    const now = Date.now();

    await this.redisService.setPrice(
      `${ticker.symbol}`,
      JSON.stringify(ticker),
    );

    // if (this.roomRegistry.has('pair:price:' + ticker.symbol)) {
      await this.redisService.publish(
        'pair:price',
        JSON.stringify({
          pair: ticker.symbol,
          data: ticker,
          ts: now,
        }),
      );
    // }
  }

  private calculateSymbol(item: string): string {
    const prefixes = ['1k_', '100k_', '1m_', '1b_'];
    for (const p of prefixes) {
      if (item.startsWith(p)) return item.replace(p, '');
    }
    return item;
  }

  private calculatePrice(item: string, price: number): number {
    if (item.includes('100k_')) return price / 100000;
    if (item.includes('1k_')) return price / 1000;
    if (item.includes('1m_')) return price / 1000000;
    if (item.includes('1b_')) return price / 1000000000;
    return price;
  }
}













// import { Injectable, Logger, OnApplicationShutdown } from '@nestjs/common';
// import { Centrifuge, Subscription } from 'centrifuge';
// import { RedisService } from 'src/redis/redis.service';
// import { ProviderBase } from './base.provider';
// import { CurrencyService } from 'src/currency/currency.service';
// import { SubscriptionRegistry } from './../SubscriptionRegistry.service';

// @Injectable()
// export class NobitexProvider extends ProviderBase implements OnApplicationShutdown {
//   readonly name = 'nobitex';
//   private logger = new Logger(NobitexProvider.name);
//   private shouldReconnect = true;

//   private client: Centrifuge | null = null;
//   private sub: Subscription | null = null;

//   private reconnectDelay = 2000;
//   private maxReconnectDelay = 30000;
//   private reconnectAttempts = 0;
//   private maxReconnectAttempts = 20;

//   private isConnecting = false;
//   private reconnectTimeout: NodeJS.Timeout | null = null;
//   private keepAliveInterval: NodeJS.Timeout | null = null;

//   constructor(
//     private readonly redisService: RedisService,
//     private readonly currencyService: CurrencyService,
//     private readonly roomRegistry: SubscriptionRegistry,
//   ) {
//     super();
//   }

//   protected getSocketUrl(): string {
//     return 'wss://ws.nobitex.ir/connection/websocket';
//   }

//   async connect(): Promise<void> {
//     this.logger.log('[nobitex] try to connect');

//     if (this.client && this.client.state === 'connected') {
//       this.logger.debug('[nobitex] already connected');
//       return;
//     }

//     if (this.isConnecting) {
//       this.logger.debug('[nobitex] connection already in progress');
//       return;
//     }

//     this.isConnecting = true;

//     try {
//       await this.cleanupClient();

//       this.client = new Centrifuge(this.getSocketUrl(), { debug: false });

//       this.sub = this.client.newSubscription('public:market-stats-all');
//       this.sub.on('publication', (ctx) => this.onMessage(ctx.data));

//       this.client.on('connected', () => {
//         this.logger.log('[nobitex] connected');
//         this.isConnected = true;
//         this.isConnecting = false;
//         this.reconnectAttempts = 0;
//         this.reconnectDelay = 2000;
//         this.clearReconnectTimeout();
//         this.startKeepAlive();
//       });

//       this.client.on('disconnected', (ctx) => {
//         this.logger.warn(`[nobitex] disconnected: ${ctx.reason} (code=${ctx.code})`);
//         this.isConnected = false;
//         this.isConnecting = false;
//         this.stopKeepAlive();

//         if (this.shouldReconnect) {
//           this.scheduleReconnect();
//         }
//       });

//       this.client.on('error', (err) => {
//         this.logger.error(`[nobitex] error: ${JSON.stringify(err)}`);
//       });

//       this.sub.on('subscribed', () => {
//         this.logger.log('[nobitex] subscription active');
//       });

//       this.sub.on('error', (err) => {
//         this.logger.error(`[nobitex] subscription error: ${JSON.stringify(err)}`);
//       });

//       this.client.connect();
//       this.sub.subscribe();
//     } catch (err) {
//       this.isConnecting = false;
//       this.logger.error(`[nobitex] connect exception: ${err}`);
//       if (this.shouldReconnect) {
//         this.scheduleReconnect();
//       }
//     }
//   }

//   private scheduleReconnect() {
//     if (!this.shouldReconnect) return;
//     if (this.reconnectTimeout) return;

//     if (this.reconnectAttempts >= this.maxReconnectAttempts) {
//       this.logger.error(`[nobitex] Max reconnect attempts (${this.maxReconnectAttempts}) reached`);
//       return;
//     }

//     this.reconnectAttempts++;
//     const delay = Math.min(this.reconnectDelay, this.maxReconnectDelay);

//     this.logger.warn(
//       `[nobitex] reconnecting in ${delay}ms (attempt ${this.reconnectAttempts}/${this.maxReconnectAttempts})`,
//     );

//     this.reconnectTimeout = setTimeout(async () => {
//       this.reconnectTimeout = null;

//       await this.cleanupClient();
//       this.isConnected = false;
//       this.isConnecting = false;

//       await this.connect();

//       if (!this.isConnected) {
//         this.reconnectDelay = Math.min(this.reconnectDelay * 2, this.maxReconnectDelay);
//         this.scheduleReconnect();
//       }
//     }, delay);
//   }

//   private async cleanupClient() {
//     this.stopKeepAlive();

//     if (this.sub) {
//       try {
//         this.sub.unsubscribe();
//       } catch {}
//       this.sub = null;
//     }

//     if (this.client) {
//       try {
//         this.client.disconnect();
//       } catch {}
//       this.client = null;
//     }
//   }

//   private clearReconnectTimeout() {
//     if (this.reconnectTimeout) {
//       clearTimeout(this.reconnectTimeout);
//       this.reconnectTimeout = null;
//     }
//   }

//   private startKeepAlive() {
//     this.stopKeepAlive();
//     this.keepAliveInterval = setInterval(() => {
//       if (!this.client || !this.isConnected) return;

//       try {
//         const rawWs = (this.client as any)?.transport?.ws;
//         if (rawWs && rawWs.readyState === 1) {
//           rawWs.send('2');
//           this.logger.debug('[nobitex] keep-alive sent');
//         }
//       } catch {
//         this.logger.debug('[nobitex] keep-alive send failed');
//       }
//     }, 25000);
//   }

//   private stopKeepAlive() {
//     if (this.keepAliveInterval) {
//       clearInterval(this.keepAliveInterval);
//       this.keepAliveInterval = null;
//     }
//   }

//   async disconnect(): Promise<void> {
//     this.shouldReconnect = false;
//     this.clearReconnectTimeout();
//     await this.cleanupClient();
//     this.isConnected = false;
//     this.isConnecting = false;
//   }

//   async onApplicationShutdown(signal?: string) {
//     this.logger.log(`Shutting down (${signal}), closing Nobitex connection`);
//     await this.disconnect();
//   }

//   protected async onMessage(data: any) {
//     if (!data) return;
//     const now = Date.now();

//     for (const [pair, itemTemp] of Object.entries(data)) {
//       const item = itemTemp as any;
//       const key = this.calculateSymbol(pair).replace(/-/g, '_').toUpperCase();
//       const [crypto, base_crypto] = key.split('_');
//       let ticker;

//       if (base_crypto === 'USDT') {
//         ticker = {
//           symbol: key,
//           last: await this.calculatePrice(key, item.latest),
//           high24h: await this.calculatePrice(key, item.dayHigh),
//           low24h: await this.calculatePrice(key, item.dayLow),
//           volume: item.volumeSrc,
//           quoteVolume: item.volumeDst,
//           changePercent: item.dayChange,
//           timestamp: now,
//         };
//         await this.handleTicker(ticker).catch(console.error);
//       } else if (base_crypto === 'RLS') {
//         if (crypto === 'USDT') continue;
//         ticker = {
//           symbol: `${crypto}_IRT`,
//           last: (await this.calculatePrice(key, item.latest)) / 10,
//           high24h: (await this.calculatePrice(key, item.dayHigh)) / 10,
//           low24h: (await this.calculatePrice(key, item.dayLow)) / 10,
//           volume: item.volumeSrc,
//           quoteVolume: item.volumeDst,
//           changePercent: item.dayChange,
//           timestamp: now,
//         };
//         await this.handleTicker(ticker).catch(console.error);
//       }
//     }
//   }

//   private async handleTicker(ticker: any) {
//     const now = Date.now();
//     await this.redisService.setPrice(`${ticker.symbol}`, JSON.stringify(ticker));

//     if (this.roomRegistry.has('pair:price:' + ticker.symbol)) {
//       await this.redisService.publish(
//         'pair:price',
//         JSON.stringify({
//           pair: ticker.symbol,
//           data: ticker,
//           ts: now,
//         }),
//       );
//     }
//   }

//   private calculateSymbol(item: string): string {
//     return item.replace(/^(1k_|100k_|1m_|1b_)/, '');
//   }

//   private calculatePrice(item: string, price: number): number {
//     if (item.includes('1k')) return price / 1000;
//     if (item.includes('100k')) return price / 100000;
//     if (item.includes('1m')) return price / 1000000;
//     if (item.includes('1b')) return price / 1000000000;
//     return price;
//   }
// }
