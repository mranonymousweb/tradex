import WebSocket, { WebSocketServer } from 'ws';
import { pg } from "../db.js";
import http from 'http';

class OrderBookFutures {
    constructor(port = 3050, intervalMs = 1000) {
        this.port = port;
        this.intervalMs = intervalMs;
        this.clients = new Map();
        this.cachedOrders = new Map();
        this.server = http.createServer();
        this.intervalId = null;
    }

    start() {
        this.wss = new WebSocketServer({ noServer: true });

        this.server.on('upgrade', (request, socket, head) => {
            const url = new URL(request.url, `http://${request.headers.host}`);
            const match = url.pathname.match(/^\/futures\/order\/book\/([A-Z0-9_-]+)$/i);

            if (!match) {
                socket.destroy();
                return;
            }

            const pairs = match[1].toUpperCase();

            this.wss.handleUpgrade(request, socket, head, (ws) => {
                this.wss.emit('connection', ws, pairs);
            });
        });

        this.wss.on('connection', (ws, pairs) => this.handleConnection(ws, pairs));

        this.server.listen(this.port, () => {
            console.log(`OrderBook WebSocket Server Running On Port ${this.port}`);
        });

        this.intervalId = setInterval(async () => {
            await this.updateCache();
            this.broadcastOrders();
        }, this.intervalMs);
    }

    handleConnection(ws, pairs) {
        console.log(`Client Connected: ${pairs}`);

        this.clients.set(ws, { ws, pairs });

        ws.on('close', () => {
            console.log('Client Disconnected');
            this.clients.delete(ws);
        });

        ws.on('error', (err) => {
            console.error('WebSocket Error:', err.message);
            this.clients.delete(ws);
        });

        this.sendOrdersToClient({ ws, pairs }).catch(console.error);
    }

    async fetchOrdersFromDB(limit = 150, pairs = null) {
        const params = [];
        let query = 'SELECT * FROM feature_orders';

        if (pairs) {
            query += ' WHERE pairs = $1';
            params.push(pairs);
        }

        query += ` ORDER BY created_at DESC LIMIT $${params.length + 1}`;
        params.push(limit);

        try {
            const { rows } = await pg.query(query, params);
            return rows;
        } catch (err) {
            console.error('Error Fetching Orders From DB:', err.message);
            return [];
        }
    }

    async fetchLastOrder(pairs = null) {
        const params = [];
        let query = 'SELECT * FROM feature_orders';

        if (pairs) {
            query += ' WHERE pairs = $1 AND status = 3';
            params.push(pairs);
        }

        query += ' ORDER BY created_at DESC LIMIT 1';

        try {
            const { rows } = await pg.query(query, params);
            return rows[0] || null;
        } catch (err) {
            console.error('Error Fetching Last Order From DB:', err.message);
            return null;
        }
    }

    async updateCache() {
        const uniquePairs = new Set();

        for (const client of this.clients.values()) {
            uniquePairs.add(client.pairs);
        }

        for (const pairs of uniquePairs) {
            const orders = await this.fetchOrdersFromDB(150, pairs);
            const openOrders = orders.filter(o => o.status === 2).slice(0, 50);
            const closeOrders = orders.filter(o => o.status === 3).slice(0, 50);
            const lastOrder = await this.fetchLastOrder(pairs);

            this.cachedOrders.set(pairs, { openOrders, closeOrders, lastOrder });
        }
    }

    async sendOrdersToClient(client) {
        const { ws, pairs } = client;

        if (ws.readyState !== WebSocket.OPEN) return;

        const cached = this.cachedOrders.get(pairs);

        if (!cached) {
            const orders = await this.fetchOrdersFromDB(150, pairs);
            const openOrders = orders.filter(o => o.status !== 3).slice(0, 50);
            const closeOrders = orders.filter(o => o.status === 3).slice(0, 50);
            const lastOrder = await this.fetchLastOrder(pairs);

            this.cachedOrders.set(pairs, { openOrders, closeOrders, lastOrder });

            ws.send(JSON.stringify({
                type: 'all_orders',
                pairs,
                openOrder: openOrders,
                closeOrder: closeOrders,
                lastOrder: lastOrder
                    ? { price: lastOrder.enter_price || 0, side: lastOrder.side === 1 ? 'LONG' : 'SHORT' }
                    : null
            }));

            return;
        }

        ws.send(JSON.stringify({
            type: 'all_orders',
            pairs,
            openOrder: cached.openOrders,
            closeOrder: cached.closeOrders,
            lastOrder: cached.lastOrder
                ? { price: cached.lastOrder.enter_price || 0, side: cached.lastOrder.side === 1 ? 'LONG' : 'SHORT' }
                : null
        }));
    }

    broadcastOrders() {
        for (const client of this.clients.values()) {
            this.sendOrdersToClient(client).catch(err => {
                console.error('Error Sending To Client:', err.message);
            });
        }
    }

    stop() {
        clearInterval(this.intervalId);
        if (this.wss) this.wss.close(() => console.log('WebSocket Server Stopped'));
        this.clients.clear();
        this.cachedOrders.clear();
    }
}

const orderBookServer = new OrderBookFutures();
orderBookServer.start();

export default OrderBookFutures;
