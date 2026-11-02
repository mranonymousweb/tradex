import {pg} from "../db.js";
import {CurrencyPriceService} from "./CurrencyPriceService.js";
import {FutureMatchingEngine} from "./FutureMatchingEngine.js";
import {WalletService} from "./WalletService.js";

class FutureLiquidationService {
    constructor(intervalMs = 1500) {
        this.intervalMs = intervalMs;
        this.intervalId = null;
        this.priceService = new CurrencyPriceService();
        this.futureEngine = new FutureMatchingEngine();
        this.walletService = new WalletService();
    }

    start() {
        console.log("FutureLiquidationService started");
        this.intervalId = setInterval(() => this.handle(), this.intervalMs);
    }

    stop() {
        clearInterval(this.intervalId);
        console.log("FutureLiquidationService stopped");
    }

    async handle() {
        const {rows: orders} = await pg.query(`
            SELECT *
            FROM feature_orders
            WHERE is_robot = false
              AND status = 2
        `);
        if (!orders.length) return;

        for (const order of orders) {
            await this.liquidate(order);
        }
    }

    async liquidate(order) {
        const client = await pg.connect();
        try {
            await client.query("BEGIN");

            const currentPrice = await this.getMarketPrice(order.main_currency_symbol);

            // Take Profit / Stop Loss
            if (order.has_tpsl) {

                if (order.take_profit > 0 && this.checkTP(order, currentPrice)) {
                    await this.closeOrder(client, order, "tp", currentPrice);
                    await client.query("COMMIT");
                    return;
                }
                if (order.stop_loss > 0 && this.checkSL(order, currentPrice)) {
                    await this.closeOrder(client, order, "sl", currentPrice);
                    await client.query("COMMIT");
                    return;
                }
            }

            await this.processLiquidation(client, order, currentPrice);
            await client.query("COMMIT");
        } catch (err) {
            await client.query("ROLLBACK");
            console.error("Liquidation error:", err.message);
        } finally {
            client.release();
        }
    }

    async processLiquidation(client, order, currentPrice) {
        const liquidationPrice = Number(order.liq_price);
        console.log(liquidationPrice,currentPrice)
        // Order Type Long
        if (order.side === 1) {
            if (currentPrice <= liquidationPrice) {
                await this.closeOrder(client, order, "liquid", currentPrice);
                return
            }
        }
        // Order Type Short
        if (order.side === 2){

            if (currentPrice >= liquidationPrice) {
                await this.closeOrder(client, order, "liquid", currentPrice);
                return
            }
        }
      return null
    }

    async closeOrder(client, order, exitType, currentPrice) {
        const parseNumeric = (value) => {
            if (typeof value === 'number') return value;
            if (typeof value === 'string') return parseFloat(value.trim());
            if (value == null) return 0;
            return parseFloat(String(value).trim()) || 0;
        };
        const cost = parseNumeric(order.cost);
        const pnl = (exitType === 'liquid') ? -parseNumeric(order.cost) : this.futureEngine.calculatePNL(order, currentPrice);
        const total = cost + pnl;
        const final = total;
        console.log('Cost:', cost, '|PnL:', pnl, '| Total:', total);

        if (exitType !== 'liquid' && final > 0) {

            await this.walletService.depositByWallet(
                order.user_id,
                { symbol: 'USDT' },
                final,
                {
                    getKey: () => order.id,
                    constructor: { name: 'FeatureOrder' }
                },
                'feature_balance',
                'وایرز بابت معامله فیوچرز'
            );
        }

        const query = `
    UPDATE feature_orders
    SET pnl = $1, exit_price = $2, exit_type = $3, closed_at = NOW(),
        ${exitType === 'liquid' ? 'liquidation_at = NOW(),' : ''}
        status = 3
    WHERE id = $4
  `;
        await client.query(query, [pnl, currentPrice, exitType, order.id]);
    }


    async getMarketPrice(symbol) {
        const currency = await this.priceService.getFuturePrice(symbol);
        return currency?.price || 0;
    }

    checkTP(order, currentPrice) {
        return order.side === 1 ? currentPrice >= order.take_profit : currentPrice <= order.take_profit;
    }

    checkSL(order, currentPrice) {
        const  stopPrice = Number(order.stop_loss)
        return order.side === 1 ? currentPrice <= stopPrice : currentPrice >= stopPrice;
    }

}

const liquidationService = new FutureLiquidationService();
liquidationService.start();

export default FutureLiquidationService;
