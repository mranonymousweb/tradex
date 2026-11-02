import { Injectable } from '@nestjs/common';
import { RedisClient } from 'src/redis/redis.client';

@Injectable()
export class CurrencyPriceService {
  constructor(private readonly redis: RedisClient) {}

//   async getPriceOf(symbol: string, prefix: string): Promise<number | null> {
//     try {
//         const marketKey = 'spot-price';
//         const symbolKey = `${symbol}_${prefix}`;
        
//         // فقط مقدار همان کلید خاص را می‌گیریم
//         const valueRaw = await this.redis.hget(marketKey, symbolKey);
        
//         if (!valueRaw) return null;
        
//         const parsed = JSON.parse(valueRaw);
//         return parsed?.last ?? null;
//     } catch (err) {
//         return null;
//     }
// }

   async getPriceOf(symbol: string, prefix: string): Promise<number | null> {
        const marketKey = 'spot-price';
        const symbolKey = `${symbol}_${prefix}`;
        const valueRaw = await this.redis.hget(marketKey, symbolKey);
        if (!valueRaw) return null;
        try {
            const parsed = JSON.parse(valueRaw);
            return parsed?.last ?? null;
        } catch {
            return null;
        }
    }

    async getUsdtIrtPrice(): Promise<number | null> {
        const marketKey = 'spot-price';
        const symbolKey = `USDT_IRT`;
        const valueRaw = await this.redis.hget(marketKey, symbolKey);
        if (!valueRaw) return null;
        try {
            const parsed = JSON.parse(valueRaw);
            return parsed?.last ?? null;
        } catch {
            return null;
        }
    }

//   async getPriceOf(symbol: string, prefix: string): Promise<number> {
//           try {
//               const marketKey = 'spot-price';  
//               const spotDataRaw = await this.redis.hgetall(marketKey);
          
//               const spotData = {};
//               for (const [key, value] of Object.entries(spotDataRaw)) {
//                   try { spotData[key] = JSON.parse(value); }
//                   catch { spotData[key] = {}; }
//               }

//               if (symbol) {
//                 const symbolKey = `${symbol}_${prefix}`;
      
//                 return  spotData?.[symbolKey]?.last;
//             }
  
//             return null;
  
//           } catch (err) {
//               return null;
//           }
//       }


  async getFuturePriceOf(symbol: string, prefix: string): Promise<number> {
    try {

        const marketKey = 'future-price';
        const symbolKey = `${symbol}_${prefix}`;
        const valueRaw = await this.redis.hget(marketKey, symbolKey);
        if (!valueRaw) return null;
        try {
            const parsed = JSON.parse(valueRaw);
            return parsed?.price ?? null;
        } catch {
            return null;
        }
    //     const marketKey = 'binance_future_prices';  
    //     const spotDataRaw = await this.redis.hgetall(marketKey);
    
    //     const spotData = {};
    //     for (const [key, value] of Object.entries(spotDataRaw)) {
    //         try { spotData[key] = JSON.parse(value); }
    //         catch { spotData[key] = {}; }
    //     }

    //     if (symbol) {
    //     const symbolKey = `${symbol}_${prefix}`;

    //     return  spotData?.[symbolKey]?.price;
    // }

    return null;

    } catch (err) {
        return null;
    }
  }
}