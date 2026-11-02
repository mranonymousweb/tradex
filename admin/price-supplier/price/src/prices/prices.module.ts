import { Module } from '@nestjs/common';
import { MarketCurrencyModule } from 'src/market_currency/market_currency.module';
import { PricesGateway } from './spot/prices.gateway';
import { RedisModule } from 'src/redis/redis.module';
import { HelperModule } from 'src/helper/helper.module';
import { SupplierExchangeModule } from 'src/supplier-exchange/supplier-exchange.module';
import { OrderMergeService } from './spot/order-merge.service';
import { KafkaModule } from 'src/kafka/kafka.module';
import { ExchangeManager } from './spot/provider/provider.manager';
import { GateioProvider } from './spot/provider/gateio.provider';
import { ProviderFactory } from './spot/provider/provider.factory';
import { NobitexProvider } from './spot/provider/nobitex.provider';
import { CoinexProvider } from './spot/provider/coinex.provider';
import { CurrencyModule } from 'src/currency/market_currency.module';
import { ExchangeFutureManager } from './futures/proviider/provider.future.manager';
import { ProviderFutureFactory } from './futures/proviider/provider.future.factory';
import { BinanceFuturesMarketProvider } from './futures/proviider/binance.future.market.provider';
import { BinanceFuturesTickerProvider } from './futures/proviider/binance.future.ticker.provider';
import { OrderMergeServiceFuture } from './futures/order-merge.service.future';
import { PricesGatewayFuture } from './futures/prices.future.gateway';
import { SubscriptionRegistry } from './spot/SubscriptionRegistry.service';
import { SubscriptionRegistryFuture } from './futures/SubscriptionRegistry.future.service';
import { BybitFuturesProvider } from './futures/proviider/bybit.future.provider';
import { AriaGostaranProvider } from './spot/provider/ariagostaran.provider';
import { GateFuturesMarketProvider } from './futures/proviider/gateio.future.market.provider';
import { GateFuturesTickerProvider } from './futures/proviider/gateio.future.ticker.provider';
import { SpotMarketRegistryService } from './spot/spot-market-registry.service';
import { SpotTickerConverterService } from './spot/spot-ticker-converter.service';


@Module({
    imports:[
        MarketCurrencyModule,
        RedisModule,
        HelperModule,
        SupplierExchangeModule,
        KafkaModule, 
        CurrencyModule
    ],
    providers: [
        ExchangeManager,
        ProviderFactory,
        PricesGateway,
        GateioProvider,
        NobitexProvider,
        CoinexProvider,
        AriaGostaranProvider,
        OrderMergeService,

        SubscriptionRegistry,
        SubscriptionRegistryFuture,
        SpotMarketRegistryService,
        SpotTickerConverterService,

        // futuresssssssss
        ExchangeFutureManager, ProviderFutureFactory, BinanceFuturesMarketProvider, BinanceFuturesTickerProvider,
        OrderMergeServiceFuture, PricesGatewayFuture, BybitFuturesProvider,
        GateFuturesMarketProvider, GateFuturesTickerProvider
        
        
    ],
})
export class PricesModule {}

