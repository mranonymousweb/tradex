import {
  WebSocketGateway,
  WebSocketServer,
  OnGatewayInit,
  SubscribeMessage,
  ConnectedSocket,
  MessageBody,
} from '@nestjs/websockets';

import { Server, Socket } from 'socket.io';
import { Logger } from '@nestjs/common';
import { RedisService } from 'src/redis/redis.service';
import { OrderMergeService } from './order-merge.service';
import { SubscriptionRegistry } from './SubscriptionRegistry.service';

@WebSocketGateway({
  namespace: '/spot',
  cors: { origin: '*' },
  pingInterval: 10000,
  pingTimeout: 25000,
})
export class PricesGateway implements OnGatewayInit {
  private readonly logger = new Logger(PricesGateway.name);

  @WebSocketServer()
  server: Server;

  private readonly lastEmit = new Map<string, number>();
  private readonly EMIT_INTERVAL = 250;

  private readonly allOrdersSnapshots = new Map<string, any>();
  private readonly lastUpdateTime = new Map<string, number>();
  private cleanupInterval: NodeJS.Timeout;
private readonly ALLOWED_QUOTES = new Set(['USDT', 'IRT', 'ETH', 'BTC']);
  
  constructor(
    private readonly redisService: RedisService,
    private readonly orderMergeService: OrderMergeService,
    private readonly roomRegistry: SubscriptionRegistry,
  ) {}

  afterInit() {
    this.logger.log('PricesGateway initialized successfully');

    this.redisService.subscribe('pair:price', (msg) => this.handlePairPriceBroadcast(msg));
    this.redisService.subscribe('base:price', (msg) => this.handleBasePriceBroadcast(msg));
    this.redisService.subscribe('fiat:USDT_IRT', (msg) => this.handleFiatBroadcast(msg));
    this.redisService.subscribe('all:orders', (msg) => this.handleAllOrdersBroadcast(msg));
    this.redisService.subscribe('my:orders', (msg) => this.handleSimpleBroadcast(msg, 'my:orders'));


    this.redisService.subscribe('swap:base:price', (msg) => this.handleSwapPriceBroadcast(msg));


    this.cleanupInterval = setInterval(() => {
      const now = Date.now();
      for (const [pair, lastTime] of this.lastUpdateTime.entries()) {
        if (now - lastTime > 30 * 60 * 1000) { // 30 minutes
          this.allOrdersSnapshots.delete(pair);
          this.lastUpdateTime.delete(pair);
        }
      }
    }, 10 * 60 * 1000); // هر ۱۰ دقیقه


    setInterval(async () => {
      const now = Date.now();

      const USDT2IRTConvetor = await this.redisService.getUSDT2IRTConvetor();
      if (USDT2IRTConvetor) {
        await this.redisService.publish(
          'fiat:USDT_IRT',
          JSON.stringify({
            pair: USDT2IRTConvetor.pairs,
            data: {
              changePercent: USDT2IRTConvetor.change_percentage,
              high24h: USDT2IRTConvetor.high_24h,
              last: USDT2IRTConvetor.last,
              origin_last: USDT2IRTConvetor.origin_last ?? USDT2IRTConvetor.last,
              buy_last: USDT2IRTConvetor.buy_last  ?? USDT2IRTConvetor.last,
              low24h: USDT2IRTConvetor.low_24h,
              quoteVolume: USDT2IRTConvetor.quote_volume,
              symbol: USDT2IRTConvetor.pairs,
              volume: USDT2IRTConvetor.base_volume,
              timestamp: now,
            },
            ts: now,
          }),
        );
      }
      
    }, 5000);
  }

  // ==================== Broadcast Handlers ====================
    // broadcast

  private handlePairPriceBroadcast(msg: string) {
    const type = "pair:price"
    try {
      const data = JSON.parse(msg);
      const key = data.pair || '';
      const room = `pair:price:${key}` ;

      // if (!this.roomRegistry.has(room)) return;
      
      this.server.to(room).emit(type, data);
    } catch (e) {
      this.logger.error(`Error in ${type} broadcast`, e);
    }
  }

  private handleBasePriceBroadcast(msg: string) {
    const type = "base:price"
    try {
      const data = JSON.parse(msg);
       const key = data.pair || '';
      const room = `base:price:${key}`;
      // console.log("handleBasePriceBroadcast : ", room)
      this.server.to(room).emit(type, data);
    } catch (e) {
      this.logger.error(`Error in ${type} broadcast`, e);
    }
  }

    private handleSwapPriceBroadcast(msg: string) {
    const type = "swap:base:price"
    try {
      const data = JSON.parse(msg);
       const key = data.pair || '';
      const room = `base:price:${key}`;
      this.server.to(room).emit(type, data);
    } catch (e) {
      this.logger.error(`Error in ${type} broadcast`, e);
    }
  }


  private handleFiatBroadcast(msg: string) {
    try {
      const data = JSON.parse(msg);
      this.server.to('fiat:USDT_IRT').emit('fiat:USDT_IRT', data);
    } catch (e) {
      this.logger.error('Error in fiat broadcast', e);
    }
  }

  private async handleAllOrdersBroadcast(msg: string) {
    try {
      const data = JSON.parse(msg);
      const merged = await this.orderMergeService.buildMerged(
        data.pair,
        data.data.buyers,
        data.data.sellers,
        data.data.done,
        data.data.last,
        data.from
      );

      this.allOrdersSnapshots.set(data.pair, merged);
      this.lastUpdateTime.set(data.pair, Date.now());
      this.server.to(`all:orders:${data.pair}`).emit('all:orders', merged);
    } catch (e) {
      this.logger.error('Error in all:orders broadcast', e);
    }
  }

  private handleSimpleBroadcast(msg: string, eventName: string) {
    try {
      const data = JSON.parse(msg);
      const room = `${eventName}:${data.pair}`;
      this.server.to(room).emit(eventName, data);
    } catch (e) {
      this.logger.error(`Error in ${eventName} broadcast`, e);
    }
  }

  // ==================== Subscribe Handlers ====================
    // subscribe
  // @SubscribeMessage('subscribe:pair:price')
  // async subscribe(@ConnectedSocket() client: Socket, @MessageBody() body: any) {
  //   const payload = typeof body === 'string' ? JSON.parse(body) : body;

  //   const room = `pair:price:${payload.pair}`;
  //   client.join(room);

  //   this.roomRegistry.add(payload.pair);

  //   return { status: 'ok', room };
  // }

  @SubscribeMessage('subscribe:pair:price')
  async subscribe(
    @ConnectedSocket() client: Socket,
    @MessageBody() body: any,
  ) {
   

    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    const PAIR_REGEX = /^[A-Z]+_[A-Z]+$/;
    if (!Array.isArray(payload?.pair)) {
      return { status: 'error', message: 'pair must be an array' };
    }

    const rooms: string[] = [];
    const invalid: string[] = [];

    for (const pair of payload.pair) {
         

      if (typeof pair !== 'string') {
        invalid.push(pair);
        continue;
      }
      

      if (!PAIR_REGEX.test(pair)) {
        invalid.push(pair);
        continue;
      }

   
      const [base, quote] = pair.split('_');

      if (!this.ALLOWED_QUOTES.has(quote)) {
        invalid.push(pair);
        continue;
      }


      const room = `pair:price:${pair}`;

      client.join(room);

      if(payload.pair.length == 1){
        const pairePrice = await this.redisService.getPairPrice(body.pair);
        if (pairePrice) client.emit('pair:price', {pair:body.pair, data: pairePrice});

      }
      
      // this.roomRegistry.add(room);

      rooms.push(room);
    }

    return {
      status: 'ok',
      rooms,
      invalid,
    };
  }

  // @SubscribeMessage('subscribe:base:price')
  // handleSubscribeBasePrice(@ConnectedSocket() client: Socket, @MessageBody() body: any) {
  //   const payload = typeof body === 'string' ? JSON.parse(body) : body;
  //   if (!payload?.pair) return { status: 'error', message: 'pair required' };

  //   const room = `base:price:${payload.pair}`;
  //   client.join(room);
  //   this.roomRegistry.add(room);

  //   return { status: 'subscribed', room };
  //   // return this.genericSubscribe(client, body, 'base');
  // }

  @SubscribeMessage('subscribe:all:orders')
  async handleSubscribeAllOrders(@ConnectedSocket() client: Socket, @MessageBody() body: any) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `all:orders:${payload.pair}`;
    client.join(room);

    const snapshot = this.allOrdersSnapshots.get(payload.pair);
    if (snapshot) client.emit('all:orders', snapshot);

    return { status: 'subscribed', room };
  }

  @SubscribeMessage('subscribe:my:orders')
  handleSubscribeMyOrders(@ConnectedSocket() client: Socket, @MessageBody() body: any) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `my:orders:${payload.pair}`;
    client.join(room);

    return { status: 'subscribed', room };
    // return this.genericSubscribe(client, body, 'my:orders');
  }

  @SubscribeMessage('subscribe:fiat:USDT_IRT')
  async handleSubscribeFiatUsdtIrt(@ConnectedSocket() client: Socket, @MessageBody() body: any) {

    const room = `fiat:USDT_IRT`;
    client.join(room);

    const now = Date.now();
    const USDT2IRTConvetor = await this.redisService.getUSDT2IRTConvetor();
    if (USDT2IRTConvetor) client.emit('fiat:USDT_IRT', 
      
      JSON.stringify({
            pair: USDT2IRTConvetor.pairs,
            data: {
              changePercent: USDT2IRTConvetor.change_percentage,
              high24h: USDT2IRTConvetor.high_24h,
              last: USDT2IRTConvetor.last,
              origin_last: USDT2IRTConvetor.origin_last ?? USDT2IRTConvetor.last,
              buy_last: USDT2IRTConvetor.buy_last  ?? USDT2IRTConvetor.last,
              low24h: USDT2IRTConvetor.high_24h,
              quoteVolume: USDT2IRTConvetor.quote_volume,
              symbol: USDT2IRTConvetor.pairs,
              volume: USDT2IRTConvetor.base_volume,
              timestamp: now,
            },
            ts: now,
          }),
    );

    return { status: 'subscribed', room };
    // return this.genericSubscribe(client, body, 'my:orders');
  }




  // @SubscribeMessage('subscribe:swap:base:price')
  // handleSubscribeSwapBasePrice(@ConnectedSocket() client: Socket, @MessageBody() body: any) {
  //   const payload = typeof body === 'string' ? JSON.parse(body) : body;
  //   if (!payload?.pair) return { status: 'error', message: 'pair required' };

  //   const room = `swap:base:price:${payload.pair}`;
  //   client.join(room);

  //   this.roomRegistry.add(room);
  //   return { status: 'subscribed', room };
  //   // return this.genericSubscribe(client, body, 'base');
  // }





  // ==================== Unsubscribe Handlers ====================

  // @SubscribeMessage('unsubscribe:pair:price')
  // handleUnsubscribePairPrice(
  //   @ConnectedSocket() client: Socket,
  //   @MessageBody() body: any,
  // ) {
  //   const payload = Array.isArray(body) ? body[0] : body;
  //   if (!payload?.pair) return { status: 'error', message: 'pair required' };

  //   const room = `pair:price:${payload.pair}`;
  //   client.leave(room);

  //   this.logger.log(`Client ${client.id} unsubscribed from pair: ${payload.pair}`);
  //   return { status: 'unsubscribed', room };
  // }

  @SubscribeMessage('unsubscribe:pair:price')
  handleUnsubscribePairPrice(
    @ConnectedSocket() client: Socket,
    @MessageBody() body: any,
  ) {
     const payload = typeof body === 'string' ? JSON.parse(body) : body;
    const PAIR_REGEX = /^[A-Z]+_[A-Z]+$/;
    if (!Array.isArray(payload?.pair)) {
      return { status: 'error', message: 'pair must be an array' };
    }

    const rooms: string[] = [];
    const invalid: string[] = [];

    for (const pair of payload.pair) {
      if (typeof pair !== 'string') {
        invalid.push(pair);
        continue;
      }

      if (!PAIR_REGEX.test(pair)) {
        invalid.push(pair);
        continue;
      }

      const [base, quote] = pair.split('_');

      if (!this.ALLOWED_QUOTES.has(quote)) {
        invalid.push(pair);
        continue;
      }

      const room = `pair:price:${pair}`;
      client.leave(room);
      rooms.push(room);
      // if (this.roomRegistry.has(room)) this.roomRegistry.delete(room);
      this.logger.log(`Client ${client.id} unsubscribed from pair: ${pair}`);
    }

    return { status: 'unsubscribed', rooms };
  }

  // @SubscribeMessage('unsubscribe:base:price')
  // handleUnsubscribeBasePrice(
  //   @ConnectedSocket() client: Socket,
  //   @MessageBody() body: any,
  // ) {
  //   const payload = Array.isArray(body) ? body[0] : body;
  //   if (!payload?.pair) return { status: 'error', message: 'pair required' };

  //   const room = `base:price:${payload.pair}`;
  //   client.leave(room);

  //   return { status: 'unsubscribed', room };
  // }

  @SubscribeMessage('unsubscribe:all:orders')
  handleUnsubscribeAllOrders(
    @ConnectedSocket() client: Socket,
    @MessageBody() body: any,
  ) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `all:orders:${payload.pair}`;
    client.leave(room);

    return { status: 'unsubscribed', room };
  }

  @SubscribeMessage('unsubscribe:my:orders')
  handleUnsubscribeMyOrders(
    @ConnectedSocket() client: Socket,
    @MessageBody() body: any,
  ) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `my:orders:${payload.pair}`;
    client.leave(room);

    return { status: 'unsubscribed', room };
  }

   @SubscribeMessage('unsubscribe:base:price')
  handleUnsubscribeSwapBasePrice(
    @ConnectedSocket() client: Socket,
    @MessageBody() body: any,
  ) {
    const payload = Array.isArray(body) ? body[0] : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `swap:base:price:${payload.pair}`;
    client.leave(room);

    return { status: 'unsubscribed', room };
  }



  // ==================== Lifecycle Methods ====================
  handleConnection(client: Socket) {
    this.logger.log(`Client connected: ${client.id}`);
  }

handleDisconnect(client: Socket) {
  this.logger.log(`Client disconnected: ${client.id}`);

  const rooms = Array.from(client.rooms || []);

  for (const room of rooms) {
    if (room === client.id) continue;

    client.leave(room);

    const current = this.roomRegistry.get(room);

    if (!current) continue;

    if (current <= 1) {
      this.roomRegistry.delete(room);
    } else {
      this.roomRegistry.set(room, current - 1);
    }
  }
}
}