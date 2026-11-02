import { Pool } from 'pg';
import { createClient } from 'redis';
import config from './config.js';

// PostgresSQL main connection
const pg = new Pool(config.pgMain);

// Redis connection
const redisClient = createClient({
    url: config.redis.url,
    password: config.redis.password,
});

// connect Postgres
const connectPostgres = async () => {
    try {
        await pg.connect();
        console.log('✅ Connected to PostgreSQL');
    } catch (error) {
        console.error('❌ PostgreSQL connection failed:', error.message);
    }
};

// connect Redis
const connectRedis = async () => {
    try {
        await redisClient.connect();
        console.log('✅ Connected to Redis');
    } catch (error) {
        console.error('❌ Redis connection failed:', error.message);
    }
};

export { pg, redisClient, connectPostgres, connectRedis };
