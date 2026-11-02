import {redisClient, pg} from "../db.js";

// this cron job receive usdt price from nobitex every 5s
class NobitexUsdtPrice {
    constructor() {
        this.options = {}; // cash config
        this.loadConfig(); // loaded config
        this.data = null;
        //updated config every 5 minute
        setInterval(() => this.loadConfig(), 5 * 60 * 1000);
    }

    // receive config from database
    async loadConfig() {
        try {
            const res = await pg.query(`
                SELECT *
                FROM options
                WHERE key IN ('usdt_price_type'
                    , 'usdt_manual_price'
                    , 'usdt_price_change_percentage'
                    , 'usdt_price_data'
                    , 'usdt_price_supplier')
            `);

            // change to  key => value
            this.options = res.rows.reduce((acc, row) => {
                acc[row.key] = row.value;
                return acc;
            }, {});
            console.log("✅ Config loaded", this.options);
        } catch (err) {
            console.error("❌ Error loading config:", err.message);
        }
    }

    //connect to nobitex api
    async connect() {
        const response = await fetch('https://apiv2.nobitex.ir/market/stats?srcCurrency=usdt&dstCurrency=rls');
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        const data = await response.json();
        const price = data?.stats?.['usdt-rls'];
        if (!price) throw new Error("❌ 'usdt-rls' not found");

        const result = {
            symbol: 'USDT',
            pairs: 'USDT_IRT',
            last: this.priceCalculation(parseFloat(price.latest) / 10),
            lowest_ask: parseFloat(price.bestBuy) / 10,
            highest_bid: parseFloat(price.bestSell) / 10,
            change_percentage: parseFloat(price.dayChange),
            base_volume: parseFloat(price.volumeDst),
            quote_volume: parseFloat(price.volumeSrc),
            high_24h: parseFloat(price.dayHigh) / 10,
            low_24h: parseFloat(price.dayLow) / 10,
        };

        // await this.onPriceUpdate(result);
        await this.onPriceUpdateNest(result);
    }

    //connect to wallex api
    async connectWallex() {
        const response = await fetch('https://api.wallex.ir/v1/markets');
        const data = await response.json();
        const price = data['result']['symbols']['USDTTMN'];
        this.data = price;
        const result = {
            symbol: 'USDT',
            pairs: 'USDT_IRT',
            last: this.priceCalculation(parseFloat(price.stats.lastPrice)),
            lowest_ask: parseFloat(price.stats.askPrice),
            highest_bid: parseFloat(price.stats.bidPrice),
            change_percentage: parseFloat(price.stats['24h_ch']),
            base_volume: parseFloat(price.stats['24h_volume']),
            quote_volume: parseFloat(price.stats['24h_quoteVolume']),
            high_24h: parseFloat(price.stats['24h_highPrice']),
            low_24h: parseFloat(price.stats['24h_lowPrice']),
        };
        // await this.onPriceUpdate(result);
        await this.onPriceUpdateNest(result);
    }


    // push usdt price to price websockets
    // async onPriceUpdate(priceData) {
    //     const key = 'spot_prices';
    //     const symbolKey = 'USDT_IRT';
    //     if (!this.isValidPrice(priceData)) {
    //         console.warn(`⚠️ Invalid price for ${symbol}, keeping old price`);
    //         return;
    //     }
    //     console.log(priceData.last)
    //     try {
    //         await redisClient.hSet(key, symbolKey, JSON.stringify(priceData));
    //         await this.usdtPriceUpdate(priceData);
    //         await this.IrtPriceUpdate(priceData);
    //         console.log(`📦 Updated ${symbolKey} in Redis`);
    //     } catch (err) {
    //         console.error('❌ Redis update error:', err.message);
    //     }
    // }

    async onPriceUpdateNest(priceData) {
        const key = 'spot-price';
        const symbolKey = 'USDT_IRT';
        if (!this.isValidPrice(priceData)) {
            console.warn(`⚠️ Invalid price for ${symbol}, keeping old price`);
            return;
        }
        console.log(priceData.last)
        try {
            await redisClient.hSet(key, symbolKey, JSON.stringify(priceData));
            await this.usdtPriceUpdate(priceData);
            await this.IrtPriceUpdate(priceData);
            console.log(`📦 Updated ${symbolKey} in Redis`);
        } catch (err) {
            console.error('❌ Redis update error:', err.message);
        }
    }

    //push USDT_USDT in redist to dont receive error
    async usdtPriceUpdate(priceResult) {
        const priceData = this.options['usdt_price_data'];
        const key = 'spot-price';
        const symbolKey = 'USDT_USDT';
        let data;
        const price = this.data;
        if (priceData == 'usdt_internal') {
            data = {
                symbol: 'USDT',
                pairs: 'USDT_USDT',
                last: 1,
                changePercent: parseFloat(priceResult.change_percentage),
                volume: parseFloat(priceResult.base_volume),
                quoteVolume: parseFloat(priceResult.quote_volume),
                high24h: parseFloat(priceResult.high_24h),
                low24h: parseFloat(priceResult.low_24h),
            };
        } else {
            const raw = await redisClient.hGet(key, 'USDC_USDT');
            const usdcPrice = raw ? JSON.parse(raw) : {
                changePercent: 0,
                volume: 0,
                quoteVolume: 0,
                high24h: 0,
                low24h: 0,
            };
            data = {
                symbol: 'USDT',
                pairs: 'USDT_USDT',
                last: 1,
                changePercent: usdcPrice.changePercent,
                volume: usdcPrice.volume,
                quoteVolume: usdcPrice.quoteVolume,
                high24h: usdcPrice.high24h,
                low24h: usdcPrice.low24h,
            };
        }
        try {
            await redisClient.hSet(key, symbolKey, JSON.stringify(data));
            console.log(`📦 Updated ${symbolKey} in Redis`);
        } catch (err) {
            console.error('❌ Redis update error:', err.message);
        }
    }


    async IrtPriceUpdate(priceData) {
        const key = 'spot-price';
        const symbolKey = 'IRT_USDT';
        const data = {
            symbol: 'IRT',
            pairs: 'IRT_USDT',
            last: 1 / priceData.last,
            lowest_ask: 1,
            highest_bid: 1,
            change_percentage: 1,
            base_volume: 1,
            quote_volume: 1,
            high_24h: 1,
            low_24h: 1,
        }
        try {
            await redisClient.hSet(key, symbolKey, JSON.stringify(data));
        } catch (err) {
            console.error('❌ Redis update error:', err.message);
        }

    }

    async setPrice() {
        const type = this.options['usdt_price_type'];
        const supplier = this.options['usdt_price_supplier'];
        const manual = parseFloat(this.options['usdt_manual_price'] || 0);

        if (type === 'usdt_manual_type') {
            const result = {
                symbol: 'USDT',
                pairs: 'USDT_IRT',
                last: manual,
                lowest_ask: manual,
                highest_bid: manual,
                change_percentage: 0,
                base_volume: 0,
                quote_volume: 0,
                high_24h: manual,
                low_24h: manual,
            };
            await this.onPriceUpdateNest(result);
        } else {
            if (supplier == 'nobitex') {
                await this.connect();
            } else {
                await this.connectWallex();
            }
        }
    }

    priceCalculation(price) {
        if (this.options['usdt_price_type'] == 'usdt_percentage_type') {
            let percentage = this.options['usdt_price_change_percentage']
            let extraPrice = (percentage / 100) * price
            return extraPrice + price;
        } else {
            return price;
        }
    }

    isValidPrice(priceData) {
        return (
            priceData &&
            Number.isFinite(priceData.last) &&
            priceData.last > 0
        );
    }
}


const marketPrice = new NobitexUsdtPrice();


setInterval(() => {
    marketPrice.setPrice().catch(err => console.error("❌ Nobitex fetch error:", err.message));
}, 5000); // every 5 seconds


export default NobitexUsdtPrice;
