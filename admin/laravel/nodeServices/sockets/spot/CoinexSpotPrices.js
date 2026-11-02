// CoinexSpotPrices.js
import WebSocket from "ws";
import {redisClient} from "../../db.js";
import zlib from "zlib";

class CoinexSpotPrices {
    constructor() {
        // WebSocket instance
        this.ws = null;
        // Delay before attempting to reconnect
        this.reconnectDelay = 5000;
        // Unique request ID for subscriptions/pings
        this.requestId = 1;
        // Interval ID for ping messages
        this.pingInterval = null;
    }

    // Returns CoinEx WebSocket URL for spot markets
    getStreamUrl() {
        return "wss://socket.coinex.com/v2/spot";
    }

    // Connect to the WebSocket
    connect() {
        this.ws = new WebSocket(this.getStreamUrl());

        // On successful connection
        this.ws.on("open", () => {
            console.log("✅ Connected to CoinEx Spot WebSocket");
            this.subscribeAllMarkets(); // subscribe to all markets
            this.startPing(); // start sending periodic pings
        });

        // On receiving a message
        this.ws.on("message", data => this.handleMessage(data));

        // On WebSocket error
        this.ws.on("error", err => {
            console.error("❌ CoinEx WebSocket error:", err.message);
            this.ws.close();
        });

        // On connection close
        this.ws.on("close", () => {
            console.warn("🔌 CoinEx WebSocket closed. Reconnecting...");
            if (this.pingInterval) clearInterval(this.pingInterval);
            setTimeout(() => this.connect(), this.reconnectDelay);
        });
    }

    // Start sending ping messages every 20 seconds
    startPing() {
        this.pingInterval = setInterval(() => {
            if (this.ws.readyState === WebSocket.OPEN) {
                this.ws.send(JSON.stringify({
                    method: "server.ping",
                    params: {},
                    id: this.requestId++
                }));
            }
        }, 20000);
    }

    // Subscribe to all available markets
    subscribeAllMarkets() {
        if (this.ws.readyState !== WebSocket.OPEN) return;

        const msg = {
            method: "state.subscribe",
            params: {market_list: []}, // empty array subscribes to all markets
            id: this.requestId++
        };

        this.ws.send(JSON.stringify(msg));
        console.log("🔔 Subscribed to all CoinEx markets");
    }

    // Handle incoming messages from the WebSocket
    async handleMessage(raw) {
        try {
            let message;

            // Decompress message if it's binary
            if (Buffer.isBuffer(raw)) {
                const decompressed = zlib.gunzipSync(raw);
                message = JSON.parse(decompressed.toString());
            } else {
                message = JSON.parse(raw.toString());
            }

            // Respond to server ping
            if (message.method === "server.ping") {
                this.ws.send(JSON.stringify({
                    method: "server.pong",
                    params: [],
                    id: this.requestId++
                }));
                return;
            }

            // Handle market data updates
            if (message.method === "state.update") {
                const list = message.data?.state_list;
                if (!list || !Array.isArray(list)) return;

                // Iterate through all market updates
                for (const ticker of list) {
                    let formattedSymbol;
                    if (ticker.market.endsWith("USDT")) {
                        formattedSymbol = ticker.market.replace("USDT", "_USDT");
                    } else {
                        continue
                    }
                    const priceData = {
                        symbol: formattedSymbol, // market symbol
                        last: parseFloat(ticker.last), // last traded price
                        high24h: parseFloat(ticker.high), // 24h high
                        low24h: parseFloat(ticker.low), // 24h low
                        volume: parseFloat(ticker.volume), // 24h volume
                        quoteVolume: parseFloat(ticker.value), // 24h quote volume
                        changePercent: null, // placeholder for future calculation
                        timestamp: Date.now() // time of update
                    };

                    // Save price data to Redis
                    await this.onPriceUpdate(priceData.symbol, priceData);
                }
            }

        } catch (err) {
            console.error("❌ Parse error:", err.message);
        }
    }

    // Update Redis with new price data if valid
    async onPriceUpdate(symbol, priceData) {
        if (!this.isValidPrice(priceData)) {
            // Skip updating if price is invalid
            console.warn(`⚠️ Invalid price for ${symbol}, keeping previous value`);
            return;
        }

        try {
            await redisClient.hSet("spot_prices", symbol, JSON.stringify(priceData));
        } catch (err) {
            console.error("❌ Redis error:", err.message);
        }
    }

    // Validate price data to prevent zero or invalid updates
    isValidPrice(priceData) {
        return (
            priceData &&
            Number.isFinite(priceData.last) &&
            priceData.last > 0
        );
    }

    // Start the WebSocket connection
    start() {
        this.connect();
    }

    // Stop the WebSocket and clear ping interval
    stop() {
        this.ws?.close();
        if (this.pingInterval) clearInterval(this.pingInterval);
    }
}

export {CoinexSpotPrices};
