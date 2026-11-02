// config.js
import dotenv from 'dotenv';
import {fileURLToPath} from 'url';
import path from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
dotenv.config({path: path.resolve(__dirname, '../.env')});

export default {
    pgMain: {
        host: process.env.DB_HOST,
        port: parseInt(process.env.DB_PORT),
        database: process.env.DB_DATABASE,
        user: process.env.DB_USERNAME,
        password: process.env.DB_PASSWORD,
    },
    redis: {
        url: `redis://${process.env.REDIS_HOST}:${process.env.REDIS_PORT}`,
        password: process.env.REDIS_PASSWORD !== 'null' ? process.env.REDIS_PASSWORD : undefined,
    },
    options: {
        future: {
            orderType: {
                MARKET: 1,
                LIMIT: 2,
            },
            orderSide: {
                LONG: 1,
                SHORT: 2,
            },
            marginMode: {
                ISOLATED: 1,
                CROSS: 2,
            },
        },
        currency: {
            supplier_gate_io: 'gate_io',
            supplier_coinex: 'coinex',
            supplier_bitycle: 'bitycle',
            supplier_manual: 'manual',
            supplier_coin_market_cap: 'coin_market_cap',
            status_active: 1,
            status_inactive: 2,
        }
    },
    socket_port: process.env.SOCKET_PORT
};
