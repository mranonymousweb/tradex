import {redisClient} from "./db.js";
const key = 'binance_future_prices';
const existingDataRaw = await redisClient.get(key);
const existingData = existingDataRaw ? JSON.parse(existingDataRaw) : {};
console.log(existingData)
