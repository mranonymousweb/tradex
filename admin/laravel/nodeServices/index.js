console.log("🚀 Starting app...");

import { connectPostgres, connectRedis } from "./db.js";

await connectPostgres(); // connect to Postgres
await connectRedis(); // connect to redis

// only success when connect to database
// await import("./sockets/orderBookFutures.js");
// await import("./sockets/openOrdersFuture.js");
// await import("./sockets/binanceFuturesPrice.js");
// await import("./sockets/binanceFuturesMarketPrice.js");
// await import("./sockets/ReceiveAllFuturesPrices.js");
// USDT/IRT price is handled by Laravel: php artisan app:receive-usdt-price
// await import("./api/NobitexUsdtPrice.js");
// await import("./services/FutureLiquidationService.js");
// await import("./services/MatchingOrder.js");

//market price luncher
// await import("./sockets/spot/MarketSocketLuncher.js");
