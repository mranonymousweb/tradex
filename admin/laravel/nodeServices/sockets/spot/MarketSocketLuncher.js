// Import database and cache clients
import { pg, redisClient } from "../../db.js";

// Import app config
import config from "../../config.js";

// Internal socket server
import ReceiveAllMarketPrices from "./ReceiveAllMarketPrices.js";

// Supplier handlers
import { GateSpotPrice } from "./GateSpotPrices.js";

export default class MarketSocketLauncher {
    constructor() {
        this.supplier = null;
        this.symbols = [];
        this.priceWebSocket = null;
        this.gateInstance = null;

        // Graceful shutdown handler
        process.on("SIGINT", () => this.shutdown());
    }

    // Get first active supplier from DB
    async fetchActiveSupplier() {
        const result = await pg.query(`
            SELECT *
            FROM supplier_exchanges
            WHERE name = price_supplier
              AND price_supplier IS NOT NULL
            ORDER BY id ASC
                LIMIT 1
        `);
        this.supplier =
            result.rows[0]?.price_supplier ??
            config.options.currency.supplier_gate_io;

        if (!this.supplier) {
            console.error("⛔ No active supplier found!");
        }
    }


    // Get active symbols for the current supplier
    async fetchActiveSymbols() {
        const result = await pg.query(`
            SELECT symbol
            FROM currencies
            WHERE status = '${config.options.currency.status_active}'
            AND symbol != 'USDT'
            AND price_receiver = '${this.supplier}'
            AND type = 1
            ORDER BY rank
                LIMIT 2000
        `);
        this.symbols = result.rows.map(row => `${row.symbol}`);

        if (!this.symbols.length) {
            console.error("⛔ No symbols found for supplier:", this.supplier);
            return;
        }
    }

    // Start supplier-specific WebSocket
    async startSupplierSocket() {
        switch (this.supplier) {
            case config.options.currency.supplier_gate_io:
                console.log("🔌 Starting Gate.io Spot Socket...");
                this.gateInstance = new GateSpotPrice(this.symbols);
                this.gateInstance.start();
                break;

            case config.options.currency.supplier_coinex:
                console.log("🔌 Starting CoinEx Spot Socket...");
                const { CoinexSpotPrices } = await import("./CoinexSpotPrices.js");
                this.coinexInstance = new CoinexSpotPrices(this.symbols);
                this.coinexInstance.start();
                break;
            case config.options.currency.supplier_bitycle :
                console.log("🔌 Starting Bitycle Spot api...");
            default:
                console.warn("⚠️ Supplier not supported:", this.supplier);
                return;
        }
    }

    // Start internal socket server
    startInternalSocket() {
        this.priceWebSocket = new ReceiveAllMarketPrices(this.supplier);
        this.priceWebSocket.start();
    }

    // Graceful shutdown logic
    async shutdown() {
        console.log("\n🛑 Gracefully shutting down...");

        try {
            if (this.gateInstance) {
                console.log("🧹 Stopping Gate WebSocket...");
                this.gateInstance.stop();
            }

            if (this.priceWebSocket) {
                console.log("🧹 Stopping Internal WebSocket...");
                this.priceWebSocket.stop();
            }

            await redisClient.quit();
            console.log("✅ Redis disconnected");
        } catch (err) {
            console.error("❌ Error during shutdown:", err.message);
        }

        process.exit(0);
    }

    // Main boot function
    async launch() {
        await this.fetchActiveSupplier();
        await this.fetchActiveSymbols();
        await this.startSupplierSocket();
        this.startInternalSocket();
    }
}

const luncher = new MarketSocketLauncher();
luncher.launch();
