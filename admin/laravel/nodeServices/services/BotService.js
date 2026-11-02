import {CurrencyPriceService} from './CurrencyPriceService.js';
import { Decimal } from 'decimal.js';
import moment from 'moment-timezone';
import {pg} from "../db.js";
class BotService {
    constructor() {
        this.priceService = new CurrencyPriceService();
        this.defaultOptions = {
            main_currency_symbol: 'BTC',
            base_currency_symbol: 'USDT',
            buy_range_percentage: '0.01',
            done_buy_range_percentage: '0.0',
            sell_range_percentage: '0.1',
            done_sell_range_percentage: '0.0',
            volume_from: '1.0',
            volume_to: '2.0',
            status: 1
        };
    }

    // Helper function to get robot config
    async getActiveBot() {
        try {
            const {rows} = await pg.query(`SELECT * FROM robot_creators WHERE status = 1`);
            return  rows.map(bot => ({...this.defaultOptions, ...bot}));

        } catch (err) {
            console.error('❌ Error loading bots from DB:', err.message);
        }
    }
    // Helper function to delete  bot old order
    async  deleteOldOrder(minutes = 1) {
        try {
            const cutoffTime = now().minus({ minutes });

            const result = await pg.query(
                `
            DELETE FROM feature_orders
            WHERE is_robot = TRUE
            AND opened_at < $1
            `,
                [cutoffTime.toISO()] // or .toSQL() if DB expects 'YYYY-MM-DD HH:mm:ss'
            );
            console.log(`✅ Deleted ${result.rowCount} old orders.`);
        } catch (err) {
            console.log(`❌ Error deleting old orders: ${err.message}`);
        }
    }
    // Helper function to generate  price
    async generatePrice(bot, side = 1,done = false) {
        const currency = await this.priceService.getFuturePrice(bot.main_currency_symbol);
        const  price = currency.price || 0;

        // Determine the appropriate range percentage based on the 'done' flag
        const buyRangePercentage = done ? bot.done_buy_range_percentage : bot.buy_range_percentage;
        const sellRangePercentage = done ? bot.done_sell_range_percentage : bot.sell_range_percentage;

        // Generate a random number between min and max
        function randomUniform(min, max) {
            return Math.random() * (max - min) + min;
        }

        // Calculate buy and sell prices
        const buyPrice = price - ((price * randomUniform(0.0001, buyRangePercentage)) / 100);
        const sellPrice = price + ((price * randomUniform(0.0001, sellRangePercentage)) / 100);

        // Return the appropriate price based on the side
        return side === 2 ? sellPrice : buyPrice;
    }
    // Helper function to generate order
    async generateOrder(bot, side = 1, done = false) {
        // Get current Iran time once
        const formattedTime = moment().tz('Asia/Tehran').format('YYYY-MM-DD HH:mm:ss');

        // 1. Fetch price (assumed to be a number)
        const price = await this.generatePrice(bot, side, done);

        // 2. Generate random amount between volume_from and volume_to
        const min = parseFloat(bot.volume_from);
        const max = parseFloat(bot.volume_to);
        const amount = new Decimal(Math.random() * (max - min) + min).toDecimalPlaces(8, Decimal.ROUND_DOWN);

        // 3. Calculate total cost
        const cost = amount.mul(price).toDecimalPlaces(8, Decimal.ROUND_DOWN);

        // 4. Build and return the order object
        return {
            pairs: bot.pairs,
            main_currency_symbol: bot.main_currency_symbol,
            base_currency_symbol: bot.base_currency_symbol,
            main_currency_id: bot.main_currency_id,
            base_currency_id: bot.base_currency_id,
            side,
            type: 1, // Market Order
            quantity: amount.toString(),
            price,
            enter_price: price,
            cost: cost.toString(),
            fee: 0,
            is_robot: true,
            opened_at: formattedTime,
            closed_at: done ? formattedTime : null
        };
    }

    // Helper function to  create  bot order
    async createOrder(order) {
        try {

            // Insert order into feature_orders table
        const result =   await pg.query(`
                INSERT INTO feature_orders (
                    pairs, main_currency_symbol, base_currency_symbol,
                    main_currency_id, base_currency_id,
                    side, type, quantity, price,enter_balance,liq_price, enter_price,
                    cost, fee, is_robot, opened_at, closed_at
                ) VALUES (
                    $1, $2, $3,
                    $4, $5,
                    $6, $7, $8, $9, $10,
                    $11, $12, $13, $14, $15,$16,$17
                )
            `, [
                order.pairs,
                order.main_currency_symbol,
                order.base_currency_symbol,
                order.main_currency_id,
                order.base_currency_id,
                order.side,
                order.type,
                order.quantity,
                order.price,
                order.price,
                0,
                order.enter_price,
                order.cost,
                order.fee,
                order.is_robot,
                order.opened_at,
                order.closed_at
            ]);

            console.log(`✅ Inserted order for bot: ${result.pairs}`);

        } catch (err) {
            console.error('❌ Error creating and inserting bot orders:', err.message);
        }
    }

}

export {BotService};


