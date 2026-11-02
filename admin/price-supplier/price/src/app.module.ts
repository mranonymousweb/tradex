import { forwardRef, Module } from '@nestjs/common';
import { DbModule } from './db/db.module';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AppEnvConfigType } from './common/type/app-env-config';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { KafkaModule } from './kafka/kafka.module';
import { MongooseModule } from '@nestjs/mongoose';
import appEnvConfig from './config/app-env.config';
import path from 'path';

import { PricesModule } from './prices/prices.module';
import { MarketCurrencyModule } from './market_currency/market_currency.module';
import { MotorMatchingModule } from './motor-matching/motor-matching.module';
import { RedisModule } from './redis/redis.module';
import { HelperModule } from './helper/helper.module';
import { SupplierExchangeModule } from './supplier-exchange/supplier-exchange.module';
import { CurrencyPriceModule } from './currency_price/currency_price.module';
import { CurrencyModule } from './currency/market_currency.module';


@Module({
  imports: [ DbModule, ConfigModule.forRoot({
      isGlobal: true,
      load: [appEnvConfig],
      envFilePath: path.resolve(__dirname, '../../.env')
    }),
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
     CurrencyPriceModule,
     KafkaModule,
    //  EventModule,
    //  MatchModule,
    //  MarketModule,
    CurrencyModule,
     PricesModule,
     MarketCurrencyModule,
     MotorMatchingModule,
     RedisModule,
     HelperModule,
     SupplierExchangeModule,
    ],
  providers: []
})
export class AppModule {}
