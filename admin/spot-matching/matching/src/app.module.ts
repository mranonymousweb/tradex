import { forwardRef, Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AppEnvConfigType } from './common/type/app-env-config';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { RedisModule } from './redis/redis.module';
import { KafkaModule } from './kafka/kafka.module';
import { MongooseModule } from '@nestjs/mongoose';
import appEnvConfig from './config/app-env.config';
import path from 'path';
import { CoreModule } from './core/core.module';
import { MatchingModule } from './matching/matching.module';
import { MarketCurrencyModule } from './market-currency/market-currency.module';
import { FutureOrderModule } from './future-order/future-order.module';
import { OptionsModule } from './options/options.module';
import { UserAssetModule } from './user-asset/user-asset.module';
import { UserTransactionModule } from './user-transaction/user-transaction.module';
import { FutureHistoryModule } from './future-history/future-history.module';
import { ScheduleModule } from '@nestjs/schedule';
import { FutureAdlSettingModule } from './future-adl-setting/future-adl-setting.module';
import { CurrencyModule } from './currency/currency.module';
import { MarketOpeningHourModule } from './market-opening-hour/market-opening-hour.module';
import { MarketModule } from './market/market.module';
import { OrderTakerBotModule } from './order-taker-bot/order-taker-bot.module';

@Module({
  imports: [  RedisModule, ConfigModule.forRoot({
      isGlobal: true,
      load: [appEnvConfig],
      envFilePath: path.resolve(__dirname, '../../.env')
    }),
     ScheduleModule.forRoot(),
     TypeOrmModule.forRootAsync({
      inject: [ConfigService],
      useFactory: (configService: ConfigService<AppEnvConfigType>) => {
        const postgresEnvConfig = configService.get('postgres', {
          infer: true,
        });

        return {
          type: 'postgres',
          database: postgresEnvConfig?.dbname,
          host: postgresEnvConfig?.url,
          port: postgresEnvConfig?.port,
          username: postgresEnvConfig?.username,
          password: postgresEnvConfig?.password,
          entities: ['dist/**/*.entity{.ts,.js}'],
          autoLoadEntities: true,
          synchronize: false,
        };
      },
    }),
    MongooseModule.forRootAsync({
      inject: [ConfigService],
      useFactory: (configService: ConfigService<AppEnvConfigType>) => {
        const mongoEnvConfig = configService.get('mongo', { infer: true });

        return {
          uri: mongoEnvConfig?.uri,
        };
      },
    }),
     KafkaModule,
     CoreModule,
     MatchingModule,
     MarketCurrencyModule,
     FutureOrderModule,
     OptionsModule,
     UserAssetModule,
     UserTransactionModule,
     FutureHistoryModule,
      FutureAdlSettingModule,
      CurrencyModule,
      // MarketOpeningHourModule,
      MarketModule,
      OrderTakerBotModule,
    ],
  controllers: [AppController],
  providers: [AppService],
  exports: [AppService],
})
export class AppModule {}
