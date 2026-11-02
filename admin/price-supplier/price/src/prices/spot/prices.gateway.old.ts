import {
  WebSocketGateway,
  WebSocketServer,
  OnGatewayInit,
  SubscribeMessage,
  ConnectedSocket,
  MessageBody,
} from '@nestjs/websockets';
import { Server, Socket } from 'socket.io';
import { SanctumService } from 'src/helper/sanctum.service';
import { RedisService } from 'src/redis/redis.service';
import { OrderMergeService } from './order-merge.service';

@WebSocketGateway({
  cors: {
    origin: '*',
  },
})
export class PricesGatewayOld implements OnGatewayInit {
  @WebSocketServer()
  server: Server;

  constructor(
    private readonly redisService: RedisService,
    private readonly sanctumService: SanctumService,
    private readonly orderMergeService: OrderMergeService,
  ) {}



  private lastEmit = new Map<string, number>();
  private EMIT_INTERVAL = 300; // ms
  private myOrdersSnapshots = new Map<string, any>();
   private allOrdersSnapshots = new Map<string, any>();

  afterInit() {

    // handle pair channel
    this.redisService.subscribe('pair:price', (msg) => {
      const data = JSON.parse(msg);
      
      const key = `${data.pair}`;
      const now = Date.now();

      const last = this.lastEmit.get(key) || 0;
      if (now - last < this.EMIT_INTERVAL) return;

      this.lastEmit.set(key, now);

      const room = `pair:${data.pair}`;
      this.server.to(room).emit('pair:price', data);
    });

    // handle base channel
    this.redisService.subscribe('base:price', (msg) => {
      const data = JSON.parse(msg);
      
      const key = `${data.pair}`;
      const now = Date.now();

      const last = this.lastEmit.get(key) || 0;
      if (now - last < this.EMIT_INTERVAL) return;

      this.lastEmit.set(key, now);

      const room = `base:${data.pair}`;
      this.server.to(room).emit('base:price', data);
    });

    // handle fiat IRT channel
    this.redisService.subscribe('fiat:USDT_IRT', (msg) => {
      const data = JSON.parse(msg);
      
      const key = `${data.pair}`;
      const now = Date.now();

      const last = this.lastEmit.get(key) || 0;
      if (now - last < this.EMIT_INTERVAL) return;

      this.lastEmit.set(key, now);

      const room = `fiat:USDT_IRT`;
      this.server.to(room).emit('fiat:USDT_IRT', data);
    });

    this.redisService.subscribe('all:orders', async (msg) => {
      const data = JSON.parse(msg);


      const merged = await this.orderMergeService.buildMerged(
        data.pair,
        data.data.buyers,
        data.data.sellers,
        data.data.done,
        data.data.last,
        data.from
      );


      // console.log("all:orders:", merged)
      // 🔥 ذخیره snapshot
      this.allOrdersSnapshots.set(data.pair, merged);
      const room = `all:orders:${data.pair}`;
      this.server.to(room).emit('all:orders', merged);
    });

    this.redisService.subscribe('my:orders', (msg) => {
      const data = JSON.parse(msg);

      // 🔥 ذخیره snapshot
      // this.myOrdersSnapshots.set(data.pair, data);
      const room = `my:orders:${data.pair}`;
      this.server.to(room).emit('my:orders', data);
    });

    this.redisService.subscribe('future:orders', (msg) => {
      const data = JSON.parse(msg);

      // 🔥 ذخیره snapshot
      // this.myOrdersSnapshots.set(data.pair, data);
      const room = `future:orders:${data.pair}`;
      this.server.to(room).emit('future:orders', data);
    });
  }
    @SubscribeMessage('subscribe:pair:price')
    async handleSubscribePairPrice(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {  
      const payload = Array.isArray(body) ? body[0] : body;

      const room = `pair:${payload.pair}`;

     
           // ✅ اگر snapshot موجوده، همون لحظه بفرست
      const pairePrice = await this.redisService.getPairPrice(payload.pair);
      console.log("pairePrice",pairePrice)

      if (pairePrice) {
        client.emit('pair:price', pairePrice);
      }

      client.join(room);

      return {
        status: 'subscribed',
        room,
      };
    }

    @SubscribeMessage('unsubscribe:pair:price')
    handleUnsubscribePairPrice(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {
      const payload =
        typeof body === 'string' ? JSON.parse(body) : body;

      const room = `pair:${payload.pair}`;

     

      client.leave(room);

      return { status: 'unsubscribed', room };
    }

    @SubscribeMessage('subscribe:base:price')
    handleSubscribeBasePrice(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {  
      const payload = Array.isArray(body) ? body[0] : body;

      const room = `base:${payload.pair}`;


      client.join(room);

      return {
        status: 'subscribed',
        room,
      };
    }

    @SubscribeMessage('unsubscribe:base:price')
    handleUnsubscribe(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {
      const payload =
        typeof body === 'string' ? JSON.parse(body) : body;

      const room = `base:${payload.pair}`;

 

      client.leave(room);

      return { status: 'unsubscribed', room };
    }


    @SubscribeMessage('subscribe:fiat:USDT_IRT')
    handleSubscribeFiatPrice(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {  
      const payload = Array.isArray(body) ? body[0] : body;

      const room = `fiat:USDT_IRT`;

   

      client.join(room);

      return {
        status: 'subscribed',
        room,
      };
    }

    @SubscribeMessage('unsubscribe:fiat:USDT_IRT')
    handleUnsubscribeFiatIRT(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {
      const payload =
        typeof body === 'string' ? JSON.parse(body) : body;

      const room = `fiat:USDT_IRT`;

     

      client.leave(room);

      return { status: 'unsubscribed', room };
    }



    @SubscribeMessage('subscribe:all:orders')
    async handleSubscribeAllOrders(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {
      const payload =
        typeof body === 'string' ? JSON.parse(body) : body;

      const pair: string = payload.pair;

      const room = `all:orders:${pair}`;
      client.join(room);

   
        // ✅ اگر snapshot موجوده، همون لحظه بفرست
      const snapshot = this.allOrdersSnapshots.get(pair);

      if (snapshot) {
        client.emit('all:orders', snapshot);
      }

      return {
        status: 'subscribed',
        room,
      };
    }



    @SubscribeMessage('unsubscribe:all:orders')
    async handleUnsubscribeAllOrders(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {
      const payload =
        typeof body === 'string' ? JSON.parse(body) : body;

      const pair: string = payload.pair;

      const room = `all:orders:${pair}`;

   

      client.leave(room);

      return { status: 'unsubscribed', room };
    }

    @SubscribeMessage('subscribe:my:orders')
    async handleSubscribeMyOrders(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {
      const payload =
      typeof body === 'string' ? JSON.parse(body) : body;

      const pair: string = payload.pair;

      const room = `my:orders:${pair}`;
      client.join(room);

      
        // ✅ اگر snapshot موجوده، همون لحظه بفرست
      // const snapshot = this.myOrdersSnapshots.get(pair);

      // if (snapshot) {
        // client.emit('my:orders', snapshot);
      // }

      return {
        status: 'subscribed',
        room,
      };
    }

    @SubscribeMessage('unsubscribe:my:orders')
    async handleUnsubscribeMyOrders(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {
      const payload =
        typeof body === 'string' ? JSON.parse(body) : body;

      const pair: string = payload.pair;

      const room = `my:orders:${pair}`;

    

      client.leave(room);

      return { status: 'unsubscribed', room };
    }

    @SubscribeMessage('subscribe:future:orders')
    async handleSubscribeFutureOrders(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {
      const payload =
      typeof body === 'string' ? JSON.parse(body) : body;

      const pair: string = payload.pair;

      const room = `future:orders:${pair}`;
      client.join(room);

    
        // ✅ اگر snapshot موجوده، همون لحظه بفرست
      // const snapshot = this.futureOrdersSnapshots.get(pair);

      // if (snapshot) {
        // client.emit('future:orders', snapshot);
      // }

      return {
        status: 'subscribed',
        room,
      };
    }

    @SubscribeMessage('unsubscribe:future:orders')
    async handleUnsubscribeFutureOrders(
      @ConnectedSocket() client: Socket,
      @MessageBody() body: any,
    ) {
      const payload =
        typeof body === 'string' ? JSON.parse(body) : body;

      const pair: string = payload.pair;

      const room = `future:orders:${pair}`;

  

      client.leave(room);

      return { status: 'unsubscribed', room };
    }

    // ✅ وقتی کاربر وصل می‌شود
  handleConnection(client: Socket) {
    // console.log(`[WS] client connected: ${client.id}`);
  }

  // ✅ وقتی کاربر قطع می‌شود
  handleDisconnect(client: Socket) {
    // console.log(`[WS] client disconnected: ${client.id}`);
  }

}
