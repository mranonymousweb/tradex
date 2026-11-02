
import WebSocket from 'ws';
import { redisClient, pg } from "../db.js";

class BinanceFuturesPrice {
    constructor(symbols = []) {
        this.symbols = symbols.map(s => s.toLowerCase());
        this.ws = null;
        this.reconnectDelay = 5000;
    }

    getStreamUrl() {
        const streamQuery = this.symbols.map(symbol => `${symbol}@ticker`).join('/');
        return `wss://fstream.binance.com/stream?streams=${streamQuery}`;
    }

    connect() {
        const wsUrl = this.getStreamUrl();
        this.ws = new WebSocket(wsUrl);

        this.ws.on('open', () => {
            console.log('✅ WebSocket connection opened for:', this.symbols.join(', '));
        });

        this.ws.on('message', (data) => {
            this.handleMessage(data);
        });

        this.ws.on('error', (err) => {
            console.error('❌ WebSocket error:', err.message);
            setTimeout(() => {
                this.reconnect();
            }, this.reconnectDelay);
        });

        this.ws.on('close', () => {
            console.warn('🔌 WebSocket closed. Reconnecting in 5s...');
            setTimeout(() => {
                this.reconnect();
            }, this.reconnectDelay);
        });
    }

    async handleMessage(data) {
        const parsed = JSON.parse(data);
        if (!parsed.data || !parsed.stream) return;

        const ticker = parsed.data;
        const symbolKey = `${ticker.s.replace('USDT', '')}_USDT`;

        const priceData = {
            symbol: ticker.s.replace('USDT',''),
            pairs : symbolKey,
            price: parseFloat(ticker.c),
            change: parseFloat(ticker.p),
            changePercent: parseFloat(ticker.P),
            high: parseFloat(ticker.h),
            low: parseFloat(ticker.l),
            volume: parseFloat(ticker.v),
            quoteVolume: parseFloat(ticker.q),
            eventTime: ticker.E,
        };
        await this.onPriceUpdate(symbolKey, priceData);
    }

    async onPriceUpdate(symbolKey, priceData) {
        const key = 'binance_future_prices';

        try {

            await redisClient.hSet(key, symbolKey, JSON.stringify(priceData));
            // // دریافت آبجکت موجود از Redis
            // const existingDataRaw = await redisClient.get(key);
            // const existingData = existingDataRaw ? JSON.parse(existingDataRaw) : {};
            //
            // // بروزرسانی داده مخصوص این symbol
            // existingData[symbolKey] = priceData;
            //
            // // ذخیره مجدد در Redis
            // await redisClient.set(key, JSON.stringify(existingData));

            // اختیاری: نمایش لاگ
            console.log(`📦 Updated ${symbolKey} in Redis`);
        } catch (err) {
            console.error('❌ Redis update error:', err.message);
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
        const res = await pg.query(`SELECT main_currency_symbol
                                    FROM market_currencies
                                    WHERE status = 1 AND type = 2
                                      AND main_currency_symbol NOT in ('USDT')`);
        const symbols = res.rows.map(row => row.main_currency_symbol + 'USDT');

        if (!symbols.length) {
            console.error('⛔ No symbols found in database.');
           return
        }
        console.log(symbols)
        const ticker = new BinanceFuturesPrice(symbols);
        ticker.start();
    } catch (err) {
        console.error('❌ Error fetching symbols from DB:', err.message);
        return
    }
};
export {startFromDatabase, BinanceFuturesPrice}

startFromDatabase();

























































































// // combinedStreamClass.mjs
// import WebSocket from 'ws';
// import {redisClient, pg} from "./db.js";
//
// class BinanceCombinedTicker {
//     constructor(symbols = []) {
//         this.symbols = symbols.map(s => s.toLowerCase());
//         this.ws = null;
//         this.reconnectDelay = 5000;
//     }
//
//     getStreamUrl() {
//         const streamQuery = this.symbols.map(symbol => `${symbol}@ticker`).join('/');
//         return `wss://fstream.binance.com/stream?streams=${streamQuery}`;
//     }
//
//     connect() {
//         const wsUrl = this.getStreamUrl();
//         this.ws = new WebSocket(wsUrl);
//
//         this.ws.on('open', () => {
//             console.log('✅ WebSocket connection opened for:', this.symbols.join(', '));
//         });
//
//         this.ws.on('message', (data) => {
//             this.handleMessage(data);
//         });
//
//         this.ws.on('error', (err) => {
//             console.error('❌ WebSocket error:', err.message);
//             setTimeout(() => {
//                 this.reconnect();
//             }, this.reconnectDelay);
//         });
//
//         this.ws.on('close', () => {
//             console.warn('🔌 WebSocket closed. Reconnecting in 5s...');
//             setTimeout(() => {
//                 this.reconnect();
//             }, this.reconnectDelay);
//         });
//     }
//
//     handleMessage(data) {
//         const parsed = JSON.parse(data);
//
//         if (!parsed.data || !parsed.stream) return;
//
//         const ticker = parsed.data;
//         const priceData = {
//             symbol: ticker.s,
//             price: parseFloat(ticker.c),
//             change: parseFloat(ticker.p),
//             changePercent: parseFloat(ticker.P),
//             high: parseFloat(ticker.h),
//             low: parseFloat(ticker.l),
//             volume: parseFloat(ticker.v),
//             quoteVolume: parseFloat(ticker.q),
//             eventTime: ticker.E,
//         };
//
//         this.onPriceUpdate(priceData);
//     }
//
//     onPriceUpdate(priceData) {
//         console.log('📊 New price data:', priceData);
//     }
//
//     reconnect() {
//         console.log('🔄 Reconnecting...');
//         this.connect();
//     }
//
//     start() {
//         this.connect();
//     }
//
//     stop() {
//         if (this.ws) {
//             this.ws.close();
//         }
//     }
// }
//
//
// const startFromDatabase = async () => {
//     try {
//         const res = await pg.query(`SELECT symbol
//                                     FROM currencies
//                                     WHERE status = 1
//                                       AND symbol NOT in ('USDT')`);
//         const symbols = res.rows.map(row => row.symbol + 'USDT');
//
//         if (!symbols.length) {
//             console.error('⛔ No symbols found in database.');
//             process.exit(1);
//         }
//         console.log(symbols)
//         const ticker = new BinanceCombinedTicker(symbols);
//         ticker.start();
//     } catch (err) {
//         console.error('❌ Error fetching symbols from DB:', err.message);
//         process.exit(1);
//     }
// };
// export {startFromDatabase, BinanceCombinedTicker}
//
// startFromDatabase();
//
// // const symbols = ['btcusdt', 'ethusdt', 'bnbusdt'];
// // const ticker = new BinanceCombinedTicker(symbols);
// // ticker.start();


























































































// // combinedStreamClass.mjs
// import WebSocket from 'ws';
// import {redisClient, pg} from "./db.js";
//
// class BinanceCombinedTicker {
//     constructor(symbols = []) {
//         this.symbols = symbols.map(s => s.toLowerCase());
//         this.ws = null;
//         this.reconnectDelay = 5000;
//     }
//
//     getStreamUrl() {
//         const streamQuery = this.symbols.map(symbol => `${symbol}@ticker`).join('/');
//         return `wss://fstream.binance.com/stream?streams=${streamQuery}`;
//     }
//
//     connect() {
//         const wsUrl = this.getStreamUrl();
//         this.ws = new WebSocket(wsUrl);
//
//         this.ws.on('open', () => {
//             console.log('✅ WebSocket connection opened for:', this.symbols.join(', '));
//         });
//
//         this.ws.on('message', (data) => {
//             this.handleMessage(data);
//         });
//
//         this.ws.on('error', (err) => {
//             console.error('❌ WebSocket error:', err.message);
//             setTimeout(() => {
//                 this.reconnect();
//             }, this.reconnectDelay);
//         });
//
//         this.ws.on('close', () => {
//             console.warn('🔌 WebSocket closed. Reconnecting in 5s...');
//             setTimeout(() => {
//                 this.reconnect();
//             }, this.reconnectDelay);
//         });
//     }
//
//     handleMessage(data) {
//         const parsed = JSON.parse(data);
//
//         if (!parsed.data || !parsed.stream) return;
//
//         const ticker = parsed.data;
//         const priceData = {
//             symbol: ticker.s,
//             price: parseFloat(ticker.c),
//             change: parseFloat(ticker.p),
//             changePercent: parseFloat(ticker.P),
//             high: parseFloat(ticker.h),
//             low: parseFloat(ticker.l),
//             volume: parseFloat(ticker.v),
//             quoteVolume: parseFloat(ticker.q),
//             eventTime: ticker.E,
//         };
//
//         this.onPriceUpdate(priceData);
//     }
//
//     onPriceUpdate(priceData) {
//         console.log('📊 New price data:', priceData);
//     }
//
//     reconnect() {
//         console.log('🔄 Reconnecting...');
//         this.connect();
//     }
//
//     start() {
//         this.connect();
//     }
//
//     stop() {
//         if (this.ws) {
//             this.ws.close();
//         }
//     }
// }
//
//
// const startFromDatabase = async () => {
//     try {
//         const res = await pg.query(`SELECT symbol
//                                     FROM currencies
//                                     WHERE status = 1
//                                       AND symbol NOT in ('USDT')`);
//         const symbols = res.rows.map(row => row.symbol + 'USDT');
//
//         if (!symbols.length) {
//             console.error('⛔ No symbols found in database.');
//             process.exit(1);
//         }
//         console.log(symbols)
//         const ticker = new BinanceCombinedTicker(symbols);
//         ticker.start();
//     } catch (err) {
//         console.error('❌ Error fetching symbols from DB:', err.message);
//         process.exit(1);
//     }
// };
// export {startFromDatabase, BinanceCombinedTicker}
//
// startFromDatabase();
//
// // const symbols = ['btcusdt', 'ethusdt', 'bnbusdt'];
// // const ticker = new BinanceCombinedTicker(symbols);
// // ticker.start();
