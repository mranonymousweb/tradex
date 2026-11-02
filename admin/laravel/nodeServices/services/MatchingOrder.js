import { pg } from "../db.js";
import { CurrencyPriceService } from "./CurrencyPriceService.js";

class MatchingOrder {
    constructor() {
        this.priceService = new CurrencyPriceService();
        this.intervalId = null;
    }

    async fetchOpenOrders() {
        const query = `
            SELECT * FROM feature_orders
            WHERE status = 1
            ORDER BY created_at DESC
        `;
        const { rows } = await pg.query(query).catch(err => {
            console.error("❌ fetchOpenOrders error:", err);
            return { rows: [] };
        });
        return rows;
    }

    async fetchPrices(orders) {
        const symbols = [...new Set(orders.map(o => o.main_currency_symbol))];
        const priceMap = {};
        await Promise.all(
            symbols.map(async (symbol) => {
                try {
                    const data = await this.priceService.getFuturePrice(symbol);
                    priceMap[symbol] = data?.price ?? 0;
                } catch (err) {
                    console.error("price fetch error for", symbol, err);
                    priceMap[symbol] = 0;
                }
            })
        );
        return priceMap;
    }

    async fetchOpenPositions(orders) {
        if (orders.length === 0) return {};
        const uniqueKeys = new Set();
        const params = [];

        for (const { user_id, pairs } of orders) {
            const key = `${user_id}_${pairs}`;
            if (!uniqueKeys.has(key)) {
                uniqueKeys.add(key);
                params.push(user_id, pairs);
            }
        }

        const count = params.length / 2;
        const valuesClause = [];
        for (let i = 0; i < count; i++) {
            const idx = i * 2;
            valuesClause.push(`($${idx + 1}, $${idx + 2})`);
        }
        const vc = valuesClause.join(", ");
        const query = `
            SELECT * FROM feature_orders
            WHERE status = 2 AND (user_id, pairs) IN (${vc})
        `;
        const { rows } = await pg.query(query, params).catch(err => {
            console.error("❌ fetchOpenPositions error:", err);
            return { rows: [] };
        });
        const map = {};
        for (const pos of rows) {
            const key = `${pos.user_id}_${pos.pairs}`;
            map[key] = pos;
        }
        return map;
    }

    async matchOrders() {
        const orders = await this.fetchOpenOrders();
        if (orders.length === 0) return;

        const priceMap = await this.fetchPrices(orders);
        const openPositions = await this.fetchOpenPositions(orders);

        const processedKeys = new Set();

        for (const order of orders) {
            const { id, user_id, pairs, side, quantity,cost, enter_price: ep } = order;
            const enterPrice = parseFloat(ep);
            const currentPrice = priceMap[order.main_currency_symbol] ?? 0;

            if (isNaN(enterPrice) || currentPrice === 0) {
                console.warn(`⚠️ Skipping order ${id} — invalid price`);
                continue;
            }

            const isLong = side === 1 && enterPrice >= currentPrice;
            const isShort = side === 2 && enterPrice <= currentPrice;

            if (!isLong && !isShort) continue;

            const key = `${user_id}_${pairs}`;
            if (processedKeys.has(key)) continue;
            processedKeys.add(key);

            const existingPos = openPositions[key];

            try {
                await pg.query("BEGIN");

                if (!existingPos) {
                    // First valid order becomes the open position
                    await this.updateOrderStatusTx(id, 2);
                    openPositions[key] = {
                        ...order,
                        status: 2,
                        quantity: +order.quantity,
                        cost: +order.cost,
                        enter_price: enterPrice,
                        id: order.id
                    };
                    await pg.query("COMMIT");
                    console.log(`✅ Order ${id} promoted to open position`);
                    continue;
                }

                // Merge with existing position
                const newQty = parseFloat(existingPos.quantity) + parseFloat(order.quantity);
                const newCost = parseFloat(existingPos.cost) + parseFloat(order.cost);
                const avgPrice =
                    (parseFloat(existingPos.enter_price) * parseFloat(existingPos.quantity) +
                        enterPrice * parseFloat(order.quantity)) / newQty;

                await this.updatePositionTx(existingPos.id, avgPrice, newQty,newCost,order.side,order.liq_price);
                await this.updateOrderStatusTx(id, 2);
                await this.deleteOrder(id); // Delete after merge

                await pg.query("COMMIT");
                console.log(`✅ Order ${id} merged into position ${existingPos.id}`);
            } catch (err) {
                await pg.query("ROLLBACK");
                console.error(`❌ Transaction failed for order ${id}:`, err.message);
            }
        }
    }

    async updateOrderStatusTx(orderId, status) {
        await pg.query(`UPDATE feature_orders SET status = $1 WHERE id = $2`, [status, orderId]);
    }

    async updatePositionTx(positionId, entryPrice, quantity, cost,side,liqPrice) {
        await pg.query(
            `UPDATE feature_orders SET enter_price = $1, quantity = $2, cost = $3 ,side =$4,liq_price=$5 WHERE id = $6`,
            [entryPrice, quantity, cost,side,liqPrice, positionId]
        );
    }


    async deleteOrder(orderId) {
        await pg.query(`DELETE FROM feature_orders WHERE id = $1`, [orderId]);
        console.log(`🗑️ Order ${orderId} deleted after processing`);
    }

    start(intervalMs = 1500) {
        if (this.intervalId) return;
        this.intervalId = setInterval(() => {
            this.matchOrders().catch((err) => {
                console.error("❌ Error in matchOrders interval:", err);
            });
        }, intervalMs);
        console.log(`⏳ MatchingOrder started, running every ${intervalMs / 1000} seconds`);
    }

    stop() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
            console.log("🛑 MatchingOrder stopped");
        }
    }
}

const matchingOrder = new MatchingOrder();
matchingOrder.start();

export default matchingOrder;
