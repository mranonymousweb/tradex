import WebSocket, { WebSocketServer } from "ws";
import {pg} from "../db.js";
import http from "http";
import { CurrencyPriceService } from "../services/CurrencyPriceService.js";
import { FutureMatchingEngine } from "../services/FutureMatchingEngine.js";

class OpenOrdersFuture {
    constructor(port = 3040, intervalMs = 1000) {
        this.priceService = new CurrencyPriceService();
        this.futureEngine = new FutureMatchingEngine();
        this.port = port;
        this.intervalMs = intervalMs;
        this.clients = []; // { ws, userId, pairs }
        this.server = http.createServer();
        this.intervalId = null;
    }

    start() {
        this.wss = new WebSocketServer({ noServer: true });

        this.server.on("upgrade", (request, socket, head) => {
            const url = new URL(request.url, `http://${request.headers.host}`);
            const match = url.pathname.match(/^\/futures\/open\/order\/([0-9]+)\/([A-Z0-9_-]+)$/i);
            if (!match) {
                socket.destroy();
                return;
            }

            const userId = parseInt(match[1], 10);
            const pairs = match[2].toUpperCase();

            this.wss.handleUpgrade(request, socket, head, (ws) => {
                this.wss.emit("connection", ws, { userId, pairs });
            });
        });

        this.wss.on("connection", (ws, { userId, pairs }) =>
            this.handleConnection(ws, userId, pairs)
        );

        this.server.listen(this.port, () => {
            console.log(`Open Orders WebSocket Running On Port ${this.port}`);
        });

        this.intervalId = setInterval(() => this.broadcastOrders(), this.intervalMs);
    }

    handleConnection(ws, userId, pairs) {
        console.log(`Client Connected: User=${userId}, Pairs=${pairs}`);
        const client = { ws, userId, pairs };
        this.clients.push(client);

        ws.on("close", () => {
            console.log("Client Disconnected");
            this.clients = this.clients.filter((c) => c.ws !== ws);
        });

        ws.on("error", (err) => console.error("WebSocket Error:", err.message));
    }

    async fetchOpenOrders(userId, pairs) {
        const query = `
            SELECT * FROM feature_orders
            WHERE user_id = $1 AND pairs = $2 AND status in (1,2)
            ORDER BY created_at DESC
                LIMIT 30
        `;

        try {
            const { rows } = await pg.query(query, [userId, pairs]);
            return rows;
        } catch (err) {
            console.error("Error Fetching Open Orders:", err.message);
            return [];
        }
    }

    async sendOrdersToClient(client) {
        if (client.ws.readyState !== WebSocket.OPEN) return;

        try {
            const orders = await this.fetchOpenOrders(client.userId, client.pairs);

            // Separate open orders & positions
            const openOrders = orders.filter(o => o.status === 1);
            const positions = orders.filter(o => o.status === 2);

            // keep only one position (latest by opened_at or id)
            const latestPosition = positions.sort((a, b) => new Date(b.opened_at) - new Date(a.opened_at))[0] || null;

            // Collect unique symbols for price lookup
            const symbols = [...new Set(orders.map(o => o.main_currency_symbol))];

            // Fetch prices in parallel for efficiency
            const prices = {};
            await Promise.all(symbols.map(async s => {
                try {
                    const currency = await this.priceService.getFuturePrice(s);
                    prices[s] = currency?.price || 0;
                } catch (err) {
                    console.error(`Failed to get price for ${s}:`, err.message);
                    prices[s] = 0;
                }
            }));

            // Helper: build order DTO
            const buildOrderPayload = (o) => {
                const price = prices[o.main_currency_symbol] || 0;
                return {
                    id: o.id,
                    pairs: o.pairs,
                    main_currency_symbol: o.main_currency_symbol,
                    base_currency_symbol: o.base_currency_symbol,
                    side: o.side,
                    type: o.type,
                    leverage: o.leverage,
                    enter_price: o.enter_price,
                    margin: this.futureEngine.calculateMargin(o),
                    pnl: this.futureEngine.calculatePNL(o, price),
                    roi: this.futureEngine.calculateROI(o, price),
                    break_even: this.futureEngine.calculateBreakEvenPrice(o),
                    market_price: price,
                    margin_ratio: this.futureEngine.calculateMarginRatio(o, price),
                    liq_price: o.liq_price,
                    has_tpsl: o.has_tpsl,
                    take_profit: o.has_tpsl ? Number(o.take_profit) : null,
                    stop_loss: o.has_tpsl ? Number(o.stop_loss) : null,
                    margin_mode: o.margin_mode,
                    position_size: o.quantity * o.leverage,
                    quantity: o.quantity,
                    opened_at: o.opened_at,
                    done_at: o.done_at,
                    closed_at: o.closed_at,
                    liquidation_at: o.liquidation_at,
                    enter_balance : o.enter_balance,
                };
            };

            const payload = JSON.stringify({
                type: "open_orders",
                userId: client.userId,
                pairs: client.pairs,
                open_orders: openOrders.map(buildOrderPayload),
                position: latestPosition ? buildOrderPayload(latestPosition) : null, // ✅ only one
            });

            client.ws.send(payload);

        } catch (err) {
            console.error("Failed To Send Payload To Client:", err.message);
        }
    }


    async broadcastOrders() {
        await Promise.all(this.clients.map((c) => this.sendOrdersToClient(c)));
    }

    stop() {
        clearInterval(this.intervalId);
        if (this.wss) this.wss.close(() => console.log("WebSocket server stopped"));
        this.clients = [];
    }

}

const openOrdersServer = new OpenOrdersFuture();
openOrdersServer.start();

export default OpenOrdersFuture;
