import Redis from 'ioredis';
import { Injectable } from '@nestjs/common';
import { ConfigService, ConfigType } from '@nestjs/config';
import appEnvConfig from 'src/config/app-env.config';
@Injectable()
export class RedisService {
    public client: Redis;
    private subscriber: Redis;

    constructor(
         private readonly configService: ConfigService<ConfigType<typeof appEnvConfig>>,
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
    setPrice(symbol,  value: string) {
        // this.getSpotPrices()
        return this.client.hset("spot-price", symbol, value);
        // return this.client.set(key, value);
    }
    // async getSpotPrices() {
    //     const data = await this.client.hgetall("spot-price");
    //     console.log(data);
    //     return data;
    // }

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
        const raw = await this.client.hget("spot_prices", "USDT_IRT");
        if (!raw) return;
        return JSON.parse(raw);
        
    }

    async getPairPrice(pair) {
        
        const raw = await this.client.hget("spot-price", pair);
        // console.log("getPairPrice", raw)
        if (!raw) return;

        return JSON.parse(raw);
        
    }

    


    async getFuturePairPrice(pair) {
        const raw = await this.client.hget("future-price", pair);
        if (!raw) return;
        return JSON.parse(raw);
        
    }

    async get(key: string): Promise<string | null> {
        return this.client.get(key);
    }

    async set(key: string, value: string, ttlSeconds?: number): Promise<'OK'> {
        if (ttlSeconds && ttlSeconds > 0) {
            return this.client.set(key, value, 'EX', ttlSeconds);
        }
        return this.client.set(key, value);
    }

    async setex(key: string, ttlSeconds: number, value: string): Promise<'OK'> {
        return this.client.setex(key, ttlSeconds, value);
    }

    async del(key: string): Promise<number> {
        return this.client.del(key);
    }
}
