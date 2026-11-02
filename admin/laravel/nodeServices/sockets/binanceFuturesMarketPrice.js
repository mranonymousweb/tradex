import {pg, redisClient} from "../db.js";
import WebSocket from "ws";

class BinanceCombinedMarketPrice {
    constructor(symbols = []) {
        this.symbols = symbols.map(s => s.toLowerCase());
        this.ws = null;
        this.reconnectDelay = 5000;
    }

    getStreamUrl() {
        const streamQuery = this.symbols.map(symbol => `${symbol}@markPrice`).join('/')
        return `wss://fstream.binance.com/stream?streams=${streamQuery}`;
    }

    connect() {
        const wsUrl = this.getStreamUrl()
        this.ws = new WebSocket(wsUrl)

        this.ws.on('open', () => {
            console.log('✅ WebSocket connection opened for:', this.symbols.join(', '));
        })

        this.ws.on('message', (data) => {
            this.handleMessage(data)
        })
        this.ws.on('error', (err) => {
            console.error('❌ WebSocket error:', err.message);
            setTimeout(() => {
                this.reconnect();
            }, this.reconnectDelay);
        })
        this.ws.on('close', () => {
            console.warn('🔌 WebSocket closed. Reconnecting in 5s...');
            setTimeout(() => {
                this.reconnect();
            }, this.reconnectDelay);
        })
    }

    async handleMessage(data) {
        const parsed = JSON.parse(data)
        const symbolKey = `${parsed.data.s.replace('USDT', '')}_USDT`;
        const priceData = {
            symbol: parsed.data.s.replace('USDT', ''),
            pairs: symbolKey,
            markPrice: parseFloat(parsed.data.p),      // قیمت مارک
            indexPrice: parseFloat(parsed.data.i),     // قیمت شاخص
            fundingRate: parseFloat(parsed.data.r),    // نرخ تأمین مالی
            eventTime: parsed.data.E,                  // زمان دریافت قیمت
            fundingTime: parsed.data.T                 // زمان بعدی تأمین مالی
        };
        await this.onPriceUpdate(symbolKey, priceData)
    }

    async onPriceUpdate(symbolKey, priceData) {
        const key = 'binance_future_market_prices';
        try {
            await redisClient.hSet(key, symbolKey, JSON.stringify(priceData));
            // const existingDataRaw = await redisClient.get(key);
            // const existingData = existingDataRaw ? JSON.parse(existingDataRaw) : {};
            // existingData[symbolKey] = priceData;
            // await redisClient.set(key, JSON.stringify(existingData));
            console.log(`📦 Updated ${symbolKey} in Redis`);

        } catch (error) {
            console.error('❌ Redis update error:', error.message);
        }
    }

    reconnect() {
        console.log('🔄 Reconnecting...');
        this.connect();
    }

    start() {
        this.connect();
    }

    stop() {
        if (this.ws) {
            this.ws.close();
        }
    }
}

const startFromDatabase = async () => {
    try {
        const res = await pg.query(`SELECT *
                                    FROM market_currencies
                                    WHERE status = 1
                                      AND type = 2
                                      AND main_currency_symbol NOT IN ('USDT')`)
        const symbols = res.rows.map((row) => row.main_currency_symbol + 'USDT')
        if (!symbols.length) {
            console.error('⛔ No symbols found in database.');
          return
        }
        console.log(symbols)
        const marketPrice = new BinanceCombinedMarketPrice(symbols);
        marketPrice.start();
    } catch (error) {
        console.error('❌ Error fetching symbols from DB:', error.message);
       return
    }
}

export {
    BinanceCombinedMarketPrice
}

startFromDatabase()

