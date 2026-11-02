// GateSpotPrice.js
import WebSocket from 'ws';
import {redisClient, pg} from "../../db.js";
import config from "../../config.js";

//this socket ib for to receive spot prices in usdt from gate io
class GateSpotPrice {
    constructor(symbols = []) {
        this.symbols = symbols.map(s => `${s.toUpperCase()}_USDT`);
        this.ws = null;
        this.reconnectDelay = 5000;
        this.requestId = 1;
    }

    //url
    getStreamUrl() {
        return 'wss://api.gateio.ws/ws/v4/';
    }

    //connect to socket
    async connect() {
        this.ws = new WebSocket(this.getStreamUrl());

        this.ws.on('open', () => {
            console.log('✅ Connected to Gate.io Spot WebSocket');
            this.sendPing();
            this.pingInterval = setInterval(() => this.sendPing(), 30000);
            this.subscribeTickers();

        });

        this.ws.on('message', data => this.handleMessage(data));
        this.ws.on('error', err => {
            console.error('❌ WebSocket error:', err.message);
            this.ws.close();
        });
        this.ws.on('close', () => {

            console.warn('🔌 WebSocket closed. Reconnecting...');
            if (this.pingInterval) clearInterval(this.pingInterval);

            setTimeout(() => this.connect(), this.reconnectDelay);
        });
    }

    sendPing() {
        const msg = {
            time: Math.floor(Date.now() / 1000),
            id: this.requestId++,
            channel: "spot.ping",
            event: "ping",
            payload: []
        };
        this.ws.send(JSON.stringify(msg));
    }

    subscribeTickers() {
        const batchSize = 100; //100 by 100
        for (let i = 0; i < this.symbols.length; i += batchSize) {
            const batch = this.symbols.slice(i, i + batchSize);
            const msg = {
                time: Math.floor(Date.now() / 1000),
                id: this.requestId++,
                channel: "spot.tickers",
                event: "subscribe",
                payload: batch
            };
            this.ws.send(JSON.stringify(msg));
            // console.log(`📡 Subscribed to tickers batch (${i}–${i + batch.length - 1}):`, batch.join(', '));
        }
    }


    async handleMessage(raw) {
        const msg = JSON.parse(raw);
        if (msg.channel === 'spot.tickers' && msg.event === 'update') {
            const results = Array.isArray(msg.result) ? msg.result : [msg.result];

            for (const ticker of results) {
                if (ticker.currency_pair == 'USDT_USDT') continue;
                const priceData = {
                    symbol: ticker.currency_pair,
                    last: parseFloat(ticker.last),
                    high24h: parseFloat(ticker.high_24h),
                    low24h: parseFloat(ticker.low_24h),
                    volume: parseFloat(ticker.base_volume),
                    quoteVolume: parseFloat(ticker.quote_volume),
                    changePercent: parseFloat(ticker.change_percentage),
                    timestamp: Date.now()
                };
                await this.onPriceUpdate(priceData.symbol, priceData);
            }
        }
    }


    async onPriceUpdate(symbol, priceData) {
        const key = 'spot_prices';
        if (!this.isValidPrice(priceData)) {
            console.warn(`⚠️ Invalid price for ${symbol}, keeping old price`);
            return;
        }
        try {
            await redisClient.hSet(key, symbol, JSON.stringify(priceData));

            // const raw = await redisClient.get(key);
            // const obj = raw ? JSON.parse(raw) : {};
            // obj[symbol] = priceData;
            // await redisClient.set(key, JSON.stringify(obj));


            // console.log(`📦 Updated ${symbol} => ${priceData.last}`);
        } catch (err) {
            console.error('❌ Redis HSET error:', err.message);
        }
    }

    isValidPrice(priceData) {
        return (
            priceData &&
            Number.isFinite(priceData.last) &&
            priceData.last > 0
        );
    }


    start() {
        this.connect();
    }

    stop() {
        this.ws?.close();
    }
}
export {GateSpotPrice};
