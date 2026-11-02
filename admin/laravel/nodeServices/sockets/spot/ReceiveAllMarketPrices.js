import WebSocket, {WebSocketServer} from 'ws';
import {redisClient} from "../../db.js";
import http from 'http';
import config from "../../config.js";

// This class implements a WebSocket server that broadcasts market price updates to all connected clients.
class PriceWebSocketMarket {
    constructor(supplier, port = config.socket_port || 3020, intervalMs = 1500) {
        this.port = port;                   // Port for HTTP & WS server
        this.intervalMs = intervalMs;       // Broadcast interval in ms
        this.clients = [];                  // Array to hold all connected clients (WebSocket objects)
        this.wss = null;                   // WebSocketServer instance
        this.intervalId = null;            // ID of interval broadcasting market prices
        this.server = http.createServer(); // HTTP server to attach WebSocket to
        this.path = '/market/prices';      // WebSocket endpoint path
        this.supplier = supplier;          // Data supplier name
        this.pingInterval = null;          // ID of interval for ping/pong heartbeat
    }

    start() {
        if (this.wss) {
            // Prevent starting multiple servers on the same instance
            console.warn('⚠️ WebSocket already started.');
            return;
        }

        // Create a new WebSocketServer attached to our HTTP server and specific path
        this.wss = new WebSocketServer({
            server: this.server,
            path: this.path
        });

        // Listen for new client connections
        this.wss.on('connection', (ws) => this.handleConnection(ws));

        // Start HTTP server listening on the specified port
        this.server.listen(this.port, () => {
            const address = this.server.address();
            const host = address.address === '::' ? 'localhost' : address.address;
            const port = address.port;
            console.log(`✅ WebSocket Server running on at ws://${host}:${port}${this.path}`);
        }).on('error', (err) => {
            console.error('❌ Server listen error:', err.message);
        });

        // Start periodic broadcasting of market prices every intervalMs milliseconds
        this.intervalId = setInterval(() => this.broadcastPrices(), this.intervalMs);

        // Start a heartbeat mechanism to detect dead/unresponsive clients
        this.pingInterval = setInterval(() => {
            this.clients.forEach((ws) => {
                if (!ws.isAlive) {
                    // Client did not respond to previous ping, terminate connection
                    console.log('⚠️ Terminating unresponsive client');
                    ws.terminate();
                    // Remove terminated client from clients list
                    this.clients = this.clients.filter(client => client !== ws);
                    return;
                }

                // Mark client as not alive and send ping (client should respond with pong)
                ws.isAlive = false;
                ws.ping();
            });
        }, 10000); // every 10 seconds
    }

    handleConnection(ws) {
        console.log('📡 Client connected');

        // Mark this client as alive for heartbeat tracking
        ws.isAlive = true;

        // When client responds to ping with pong, mark it alive again
        ws.on('pong', () => {
            ws.isAlive = true;
        });

        // Add this client to the list of connected clients
        this.clients.push(ws);

        // Handle client disconnect event
        ws.on('close', () => {
            console.log('❌ Client disconnected');
            // Remove disconnected client from list
            this.clients = this.clients.filter(client => client !== ws);
        });

        // Handle client errors
        ws.on('error', (err) => {
            console.error('❌ WebSocket error:', err.message);
        });
    }

    stop() {
        if (this.pingInterval) {
            clearInterval(this.pingInterval);
        }
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
        if (this.wss) {
            this.wss.close(() => console.log('WebSocket server stopped'));
        }
        this.clients = [];
    }

    async broadcastPrices() {
        try {
            let marketData = {};
            // if (this.supplier === config.options.currency.supplier_gate_io) {
            const dataRaw = await redisClient.hGetAll('spot_prices');
            for (const [symbol, jsonStr] of Object.entries(dataRaw)) {
                try {
                    marketData[symbol] = JSON.parse(jsonStr);
                } catch (e) {
                    console.error(`❌ JSON parse error for ${symbol}:`, e.message);
                }
            }
            // }
            // let dataRaw = null;
            // if (this.supplier == 'gate_io') {
            //     // Fetch market prices from Redis key 'gate_spot_prices'
            //     dataRaw = await redisClient.get('gate_spot_prices');
            // }
            // const marketData = dataRaw ? JSON.parse(dataRaw) : {};

            // Prepare JSON payload to send to clients
            const payload = JSON.stringify({
                data: marketData
            });

            // Send the payload to every connected client whose connection is open
            for (const ws of this.clients) {
                if (ws.readyState === WebSocket.OPEN) {
                    ws.send(payload);
                } else {
                    // Remove clients that are no longer open/active
                    this.clients = this.clients.filter(client => client.readyState === WebSocket.OPEN);
                }
            }
        } catch (err) {
            console.error('❌ Error sending price updates:', err.message);
        }
    }
}

export default PriceWebSocketMarket;
