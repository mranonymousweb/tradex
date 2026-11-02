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
import { OrderMergeServiceFuture } from './order-merge.service.future';
import { SubscriptionRegistryFuture } from './SubscriptionRegistry.future.service';

@WebSocketGateway({
  namespace: '/future',
  cors: { origin: '*' },
  pingInterval: 10000,
  pingTimeout: 25000,
})
export class PricesGatewayFuture implements OnGatewayInit {
  private readonly logger = new Logger(PricesGatewayFuture.name);

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
    private readonly orderMergeServiceFuture: OrderMergeServiceFuture,
    private readonly roomRegistry: SubscriptionRegistryFuture,
    
  ) {}

  afterInit() {
    this.logger.log('PricesGateway initialized successfully');

    this.redisService.subscribe('future:pair:price', (msg) => this.handlePriceBroadcast(msg, 'future:pair:price'));
    this.redisService.subscribe('future:base:price', (msg) => this.handlePriceBroadcast(msg, 'future:base:price'));
    this.redisService.subscribe('future:fiat:USDT_IRT', (msg) => this.handleFiatBroadcast(msg));
    this.redisService.subscribe('future:all:orders', (msg) => this.handleAllOrdersBroadcast(msg));
    this.redisService.subscribe('future:my:orders', (msg) => this.handleMyOrdersBroadcast(msg, 'future:my:orders'));


    this.cleanupInterval = setInterval(() => {
      const now = Date.now();
      for (const [pair, lastTime] of this.lastUpdateTime.entries()) {
        if (now - lastTime > 30 * 60 * 1000) { // 30 minutes
          this.allOrdersSnapshots.delete(pair);
          this.lastUpdateTime.delete(pair);
        }
      }
    }, 10 * 60 * 1000); // هر ۱۰ دقیقه
  }

  // ==================== Broadcast Handlers ====================
  private handlePriceBroadcast(msg: string, type: string) {
    try {
      const data = JSON.parse(msg);
      const key = data.pair || '';
      const now = Date.now();

      // if (now - (this.lastEmit.get(key) || 0) < this.EMIT_INTERVAL) return;

      this.lastEmit.set(key, now);

      const room = type === 'future:pair:price' ? `future:pair:price:${key}` : `future:base:price:${key}`;
      this.server.to(room).emit(type, data);
    } catch (e) {
      this.logger.error(`Error in ${type} broadcast`, e);
    }
  }

  private handleFiatBroadcast(msg: string) {
    try {
      const data = JSON.parse(msg);
      this.server.to('future:fiat:USDT_IRT').emit('future:fiat:USDT_IRT', data);
    } catch (e) {
      this.logger.error('Error in fiat broadcast', e);
    }
  }

  private async handleAllOrdersBroadcast(msg: string) {
    try {
      const data = JSON.parse(msg);
      const merged = await this.orderMergeServiceFuture.buildMerged(
        data.pair,
        data.data.buyers,
        data.data.sellers,
        data.data.dones,
        data.data.last,
        data.from
      );

      this.allOrdersSnapshots.set(data.pair, merged);
      this.lastUpdateTime.set(data.pair, Date.now());
      this.server.to(`future:all:orders:${data.pair}`).emit('future:all:orders',  {pair: data.pair, data: merged});
    } catch (e) {
      this.logger.error('Error in future:all:orders broadcast', e);
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

  private handleMyOrdersBroadcast(msg: string, eventName: string) {
    try {
      const data = JSON.parse(msg);
      const room = `${eventName}:${data.pair}`;
      this.server.to(room).emit(eventName, data);
      const roomUser = `future:my:all:orders:${data.data.user_id}`;
      this.server.to(roomUser).emit("future:my:all:orders", data);
    } catch (e) {
      this.logger.error(`Error in ${eventName} broadcast`, e);
    }
  }

  // ==================== Subscribe Handlers ====================

  @SubscribeMessage('subscribe:future:pair:price')
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

      const room = `future:pair:price:${pair}`;

      client.join(room);
      this.roomRegistry.add(room);


      if(payload.pair.length == 1){
        const pairePrice = await this.redisService.getFuturePairPrice(body.pair);
        if (pairePrice) client.emit('pair:price', {pair:body.pair, data: pairePrice});
      }
      

      rooms.push(room);
    }

    return {
      status: 'ok',
      rooms,
      invalid,
    };
  }

  @SubscribeMessage('subscribe:future:all:orders')
  async handleSubscribeAllOrders(@ConnectedSocket() client: Socket, @MessageBody() body: any) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `future:all:orders:${payload.pair}`;
    client.join(room);

    const snapshot = this.allOrdersSnapshots.get(payload.pair);
    if (snapshot) client.emit('future:all:orders', {pair: body.pair, data:snapshot});

    return { status: 'subscribed', room };
  }

  @SubscribeMessage('subscribe:future:my:orders')
  handleSubscribeMyOrders(@ConnectedSocket() client: Socket, @MessageBody() body: any) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `future:my:orders:${payload.pair}`;
    client.join(room);

    return { status: 'subscribed', room };
    // return this.genericSubscribe(client, body, 'my:orders');
  }


  @SubscribeMessage('subscribe:future:my:all:orders')
  handleSubscribeMyAllOrders(@ConnectedSocket() client: Socket, @MessageBody() body: any) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    
    const { user_id } = payload;

    if (!user_id) {
      return { status: 'error', message: 'user_id is required' };
    }

    const room = `future:my:all:orders:${user_id}`;
    client.join(room);
    console.log(`User ${user_id} joined room: ${room}`);

    return { 
      status: 'subscribed', 
      channel: 'future:my:all:orders', // نام چنل مورد نظر شما
      user_id,
      rooms: room 
    };
  }

  // ==================== Unsubscribe Handlers ====================

  @SubscribeMessage('unsubscribe:future:pair:price')
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

      const room = `future:pair:price:${pair}`;
      client.leave(room);
      rooms.push(room);
      if (this.roomRegistry.has(room)) this.roomRegistry.delete(room);
      this.logger.log(`Client ${client.id} unsubscribed from pair: ${pair}`);
    }

    return { status: 'unsubscribed', rooms };
  }


  @SubscribeMessage('unsubscribe:future:base:price')
  handleUnsubscribeBasePrice(
    @ConnectedSocket() client: Socket,
    @MessageBody() body: any,
  ) {
    const payload = Array.isArray(body) ? body[0] : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `future:base:price:${payload.pair}`;
    client.leave(room);

    return { status: 'unsubscribed', room };
  }

  @SubscribeMessage('unsubscribe:future:all:orders')
  handleUnsubscribeAllOrders(
    @ConnectedSocket() client: Socket,
    @MessageBody() body: any,
  ) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `future:all:orders:${payload.pair}`;
    client.leave(room);

    return { status: 'unsubscribed', room };
  }

  @SubscribeMessage('unsubscribe:future:my:orders')
  handleUnsubscribeMyOrders(
    @ConnectedSocket() client: Socket,
    @MessageBody() body: any,
  ) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    if (!payload?.pair) return { status: 'error', message: 'pair required' };

    const room = `future:my:orders:${payload.pair}`;
    client.leave(room);

    return { status: 'unsubscribed', room };
  }

  @SubscribeMessage('unsubscribe:future:my:all:orders')
  handleUnSubscribeMyAllOrders(@ConnectedSocket() client: Socket, @MessageBody() body: any) {
    const payload = typeof body === 'string' ? JSON.parse(body) : body;
    
    const { user_id } = payload;

 
    if (!user_id) {
      return { status: 'error', message: 'user_id is required' };
    }

    const room = `future:my:all:orders:${user_id}`;
    client.leave(room);
      
    console.log(`User ${user_id} joined room: ${room}`);


     return { status: 'unsubscribed', room };
  }
  // ==================== Lifecycle Methods ====================
  handleConnection(client: Socket) {
    this.logger.log(`Client connected: ${client.id}`);
  }

  handleDisconnect(client: Socket) {
    this.logger.log(`Client disconnected: ${client.id}`);

    // پاک‌سازی تمام اتاق‌ها برای جلوگیری از ghost client
    const rooms = Array.from(client.rooms || []);
    rooms.forEach((room) => {
      if (room !== client.id) {
        client.leave(room);
      }
    });
  }
}