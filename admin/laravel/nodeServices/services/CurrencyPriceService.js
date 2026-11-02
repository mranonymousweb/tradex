import {redisClient} from "../db.js";

// Currency price service
// This service gets all currency prices from Redis and prints them.
// `checkRedisConnection` ensures Redis is connected.
// `getFuturePrice` fetches currency prices from Redis and returns them.

class CurrencyPriceService {
    static REDIS_KEYS = {
        FUTURE_PRICES: 'binance_future_prices',      // hash
        MARKET_PRICES: 'binance_future_market_prices' // hash
    };

    async checkRedisConnection() {
        if (!redisClient.isOpen) {
            await redisClient.connect();
        }
    }

    async getFuturePrice(symbol = null) {
        try {
            await this.checkRedisConnection();

            // گرفتن کل داده‌ها از Redis hash
            const futureDataRaw = await redisClient.hGetAll(CurrencyPriceService.REDIS_KEYS.FUTURE_PRICES);
            const marketDataRaw = await redisClient.hGetAll(CurrencyPriceService.REDIS_KEYS.MARKET_PRICES);

            // parse هر فیلد به JSON
            const futureData = {};
            for (const [key, value] of Object.entries(futureDataRaw)) {
                try { futureData[key] = JSON.parse(value); }
                catch { futureData[key] = {}; }
            }

            const marketData = {};
            for (const [key, value] of Object.entries(marketDataRaw)) {
                try { marketData[key] = JSON.parse(value); }
                catch { marketData[key] = {}; }
            }

            if (symbol) {
                const symbolKey = `${symbol}_USDT`;
                return {
                    ...futureData[symbolKey] ?? this.getDefaultData(symbol),
                    ...(marketData[symbolKey] ?? this.getDefaultData(symbol))
                };
            }

            return Object.entries(futureData).map(([symbolKey, data]) => ({
                ...data,
                ...(marketData[symbolKey] ?? this.getDefaultData(symbolKey))
            }));

        } catch (err) {
            console.error('Error CurrencyPriceService getFuturePrice():', err.message);
            return symbol ? this.getDefaultData(symbol) : [];
        }
    }

    getDefaultData(symbol) {
        return {
            symbol: symbol,
            pairs: `${symbol}_USDT`,
            price: 0,
            change: 0,
            changePercent: 0,
            high: 0,
            volume: 0,
            quoteVolume: 0,
            eventTime: 0,
            markPrice: 0,
            fundingRate: 0,
            fundingTime: 0
        }
    }
}

export { CurrencyPriceService };

// Usage Example

// Create Object
// const priceService = new CurrencyPriceService();
//
// // Get price
// const futurePrices = await priceService.getFuturePrice('BTC');
// for (const symbolKey in futurePrices) {
//     const data = futurePrices;
//     // Print Price
//     console.log(data.symbol,data.price);
// }

