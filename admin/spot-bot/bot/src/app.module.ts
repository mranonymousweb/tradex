import { forwardRef, Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { DbModule } from './db/db.module';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AppEnvConfigType } from './common/type/app-env-config';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { RedisModule } from './redis/redis.module';
import { CurrencyPriceModule } from './currency-price/currency-price.module';
import { EventModule } from './event/event.module';
import { KafkaModule } from './kafka/kafka.module';
import { MongooseModule } from '@nestjs/mongoose';
import appEnvConfig from './config/app-env.config';
import path from 'path';
import { CoreModule } from './core/core.module';
import { MarketCurrency } from './market-currency/entities/market-currency.entity';
import { FutureOrder } from './future-order/entities/future-order.entity';
import { MarketCurrencyModule } from './market-currency/market-currency.module';
import { FutureOrderModule } from './future-order/future-order.module';

@Module({
  imports: [ DbModule, RedisModule, ConfigModule.forRoot({
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
     CoreModule,
     MarketCurrencyModule, FutureOrderModule,
     forwardRef(() => EventModule)
    ],
  controllers: [AppController],
  providers: [AppService],
  exports: [AppService],
})
export class AppModule {}
