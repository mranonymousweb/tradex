import Redis from 'ioredis';
import { Injectable } from '@nestjs/common';
import { ConfigService, ConfigType } from '@nestjs/config';
import appEnvConfig from 'src/config/app-env.config';
import { CurrencyPriceService } from 'src/currency_price/currency_price.service';
@Injectable()
export class RedisService {
    public client: Redis;
    private subscriber: Redis;

    private buffer: { pair: string, price: number; time: Date }[] = [];
    private readonly BATCH_INTERVAL_MS = 60 * 1000; // 1 دقیقه
    private flushInterval: NodeJS.Timeout;

    constructor(
         private readonly configService: ConfigService<ConfigType<typeof appEnvConfig>>,
         private readonly currencyPriceService: CurrencyPriceService
    ) {
        const redisConfig = this.configService.get('redis', { infer: true });
        this.client = new Redis({
            host: redisConfig.host,
            port: redisConfig.port,
            password: redisConfig.password
        });

        this.subscriber = new Redis({
            host: redisConfig.host,
            port: redisConfig.port,
            password: redisConfig.password
        });

            this.client.on('error', err =>
            console.error('[Redis client error]', err),
        );

        this.subscriber.on('error', err =>
            console.error('[Redis subscriber error]', err),
        );
    }

        // ✅ SET
    async setPrice(symbol,  value: string) {
        if (!symbol.endsWith('_IRT')) {
            const parsedTicker = JSON.parse(value);
            const lastValue = parseFloat(parsedTicker.last);
            this.buffer.push({pair: symbol,  price: lastValue, time: new Date});  
        }
           
        return this.client.hset("spot-price", symbol, value);
    }
    async getSpotPrices() {
        const data = await this.client.hgetall("spot-price");
        console.log(data);
        return data;
    }

    // ✅ PUBLISH
    publish(channel: string, message: string) {
        return this.client.publish(channel, message);
    }

    // ✅ SUBSCRIBE
    subscribe(channel: string, handler: (msg: string) => void) {
        this.subscriber.subscribe(channel);
        this.subscriber.on('message', (ch, msg) => {
            if (ch === channel) handler(msg);
        });
    }

    async getUSDT2IRTConvetor() {
        const raw = await this.client.hget("spot-price", "USDT_IRT");
        if (!raw) return;
        return JSON.parse(raw);
        
    }



    async getPairPrice(pair) {
        const raw = await this.client.hget("spot-price", pair);
        if (!raw) return;
        return JSON.parse(raw);
        
    }



    onModuleInit() {
         console.log("onModuleInit")
        // شروع تایمر برای تخلیه دوره‌ای بافر
        this.flushInterval = setInterval(() => {
        this.flushBuffer();
        }, this.BATCH_INTERVAL_MS);
    }
      // متدی که هر دقیقه بافر را تخلیه می‌کند
    private async flushBuffer() {
        // console.log("flushBuffer", this.buffer)
        if (this.buffer.length === 0) return;

        // کپی از بافر و خالی کردن آن (برای جلوگیری از تداخل با در حال اضافه شدن داده‌ها)
        const dataToInsert = [...this.buffer];
        this.buffer = [];

        try {
        await this.currencyPriceService.insertMany(dataToInsert);
        console.log(`✅ ${dataToInsert.length} price records saved to MongoDB`);
        } catch (error) {
        console.error('Flush buffer failed:', error);
        // در صورت خطا، می‌توانید داده‌ها را دوباره به بافر برگردانید (اختیاری)
        // this.buffer.unshift(...dataToInsert);
        }
    }


    // manage future in redis
    async futureSetPrice(symbol,  value: string) {
        return this.client.hset("future-price", symbol, value);
    }


     async getFuturePairPrice(pair) {
        const raw = await this.client.hget("future-price", pair);
        if (!raw) return;
        return JSON.parse(raw);
        
    }


 

}
