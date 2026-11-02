// BityclePrice.js


// this cron job receive  prices from bitycle every 3s

class BityclePrices {
    async connect() {
        const response = await fetch('https://api.bitycle.com/api/exchange/source_market_price?source=binance_spot&market=BTCUSDT');
    }
}
