import WebSocket, {WebSocketServer} from 'ws';
import {redisClient} from "../db.js";
import http from 'http';

//with this class we have a websocket server for all users
class PriceWebSocketServer {
    // set config
    constructor(port = 3011, intervalMs = 2000) {
        this.port = port;
        this.intervalMs = intervalMs;
        this.clients = [];
        this.wss = null;
        this.intervalId = null;
        this.server = http.createServer();
        this.path = '/futures/prices';
    }

    // start web socket
    start() {
        this.wss = new WebSocketServer({
            server: this.server,
            path: this.path
        });
        this.wss.on('connection', (ws) => this.handleConnection(ws));
        this.server.listen(this.port, () => {
            const address = this.server.address();
            console.log(address.address);
            const host = address.address === '::' ? 'localhost' : address.address;
            const port = address.port;
            console.log(`✅ WebSocket Server running on at ws://${host}:${port}${this.path}`);
        });


        this.intervalId = setInterval(() => this.broadcastPrices(), this.intervalMs);
    }


    handleConnection(ws) {
        console.log('📡 Client connected');
        this.clients.push(ws);

        ws.on('close', () => {
            console.log('❌ Client disconnected');
            this.clients = this.clients.filter(client => client !== ws);
        });

        ws.on('error', (err) => {
            console.error('❌ WebSocket error:', err.message);
        });
    }



    //new version

    async broadcastPrices() {
        try {
            const [futureDataRaw, marketDataRaw] = await Promise.all([
                redisClient.hGetAll('binance_future_prices'),
                redisClient.hGetAll('binance_future_market_prices')
            ]);

            const combined = {};

            for (const symbolKey in futureDataRaw) {
                const futureObj = JSON.parse(futureDataRaw[symbolKey] || '{}');
                const marketObj = marketDataRaw[symbolKey] ? JSON.parse(marketDataRaw[symbolKey]) : {};

                combined[symbolKey] = {
                    ...futureObj,
                    ...marketObj
                };
            }

            const payload = JSON.stringify({ data: combined });

            for (const ws of this.clients) {
                if (ws.readyState === WebSocket.OPEN) {
                    ws.send(payload);
                }
            }
        } catch (err) {
            console.error('❌ Error sending price updates:', err.message);
        }
    }





    //first version
    // async broadcastPrices() {
    //     try {
    //         const futureRaw = await redisClient.get('binance_future_prices');
    //         const marketRaw = await redisClient.get('binance_future_market_prices');
    //
    //         const futureData = futureRaw ? JSON.parse(futureRaw) : {};
    //         const marketData = marketRaw ? JSON.parse(marketRaw) : {};
    //
    //         const combined = {};
    //
    //         for (const symbolKey in futureData) {
    //             combined[symbolKey] = {
    //                 ...futureData[symbolKey],
    //                 ...(marketData[symbolKey] || {})
    //             };
    //         }
    //
    //         const payload = JSON.stringify({
    //             data: combined
    //         });
    //
    //         for (const ws of this.clients) {
    //             if (ws.readyState === WebSocket.OPEN) {
    //                 ws.send(payload);
    //             }
    //         }
    //     } catch (err) {
    //         console.error('❌ Error sending price updates:', err.message);
    //     }
    // }

    stop() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
        if (this.wss) {
            this.wss.close(() => console.log('WebSocket server stopped'));
        }
        this.clients = [];
    }
}

const web = new PriceWebSocketServer();
web.start();
export default PriceWebSocketServer;
