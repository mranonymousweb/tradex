import { Module } from '@nestjs/common';
import { EngineRegistry } from './engine/engine-registry';
import { OrderDispatcherService } from './OrderDispatcher.service';
import { KafkaModule } from 'src/kafka/kafka.module';
import { MarketCurrencyModule } from 'src/market-currency/market-currency.module';
import { PendingOrderLoaderService } from './engine/services/pending-orders.service';
import { CoreModule } from 'src/core/core.module';
import { OrderValidationService } from './engine/services/order-validation.service';
import { OrderActivationService } from './engine/services/order-activation.service';
import { MatchingService } from './engine/services/matching.service';
import { FeeCalculationService } from './engine/services/fee-calculation.service';
import { RedisModule } from 'src/redis/redis.module';
import { FutureEngineRegistry } from './future/future-engine-registry';
import { FutureCloseValidationService } from './future/services/futureCloseValidationService';
import { FutureOrderActivationService } from './future/services/future-order-activation.service';
import { FutureOrderValidationService } from './future/services/future-order-validation.service';
import { FuturePositionMergeService } from './future/services/futurePositionMergeService.service';
import { FuturePositionCloseService } from './future/services/futurePositionCloseService';
import { FutureOrderModule } from 'src/future-order/future-order.module';
import { OptionsModule } from 'src/options/options.module';
import { FutureHistoryModule } from 'src/future-history/future-history.module';

import { AdlService } from 'src/matching/future/services/AdlService';
import { FuturePeriodicProfitService } from './future/services/future-periodic-profit.service';
import { FutureAdlSettingModule } from 'src/future-adl-setting/future-adl-setting.module';
import { CurrencyModule } from 'src/currency/currency.module';
import { MarketOpeningHourModule } from 'src/market-opening-hour/market-opening-hour.module';
import { UserAssetModule } from 'src/user-asset/user-asset.module';
import { MarketModule } from 'src/market/market.module';
import { OrderTakerBotModule } from 'src/order-taker-bot/order-taker-bot.module';


@Module({
  imports: [
    KafkaModule,
    MarketCurrencyModule,
    CoreModule,
    RedisModule,
    FutureOrderModule,
    OptionsModule,
    FutureHistoryModule,
    FutureAdlSettingModule,
    CurrencyModule,
    // MarketOpeningHourModule,
    UserAssetModule,
    MarketModule,
    OrderTakerBotModule
  ],
  providers: [
    EngineRegistry,
    OrderDispatcherService,
    PendingOrderLoaderService,
    OrderValidationService,
    OrderActivationService,
    MatchingService,
    FeeCalculationService,

    FutureEngineRegistry,
    FutureCloseValidationService,
    FutureOrderActivationService,
    FutureOrderValidationService,
    FuturePositionMergeService,
    FuturePositionCloseService,
    AdlService,
    FuturePeriodicProfitService,
  ],
  exports: [EngineRegistry, FutureEngineRegistry],
})
export class MatchingModule {}
