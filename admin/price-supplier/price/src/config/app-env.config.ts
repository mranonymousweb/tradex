export default () => ({
  // postgres
  postgres: {
    url: process.env.DB_HOST,
    port: parseInt(process.env.DB_PORT ?? '5432'),
    dbname: process.env.DB_DATABASE,
    username: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
  },

  // redis
  redis: {
    host: process.env.REDIS_HOST,
    port: parseInt(process.env.REDIS_PORT ?? '6379'),
    password: process.env.REDIS_PASSWORD,
  },

  // kafka
  kafka: {
    broker: process.env.KAFKA_BROKER,
    clientId: process.env.KAFKA_CLIENT_ID,
  },

  mongo: {
    uri: process.env.MONGO_URI,
  },
  
  REGION: process.env.REGION,
  ARIA_URL: process.env.ARIA_URL

  
});