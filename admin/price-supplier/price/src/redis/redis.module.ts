import { Module } from '@nestjs/common';
import { RedisService } from './redis.service';
import { CurrencyPriceModule } from 'src/currency_price/currency_price.module';

@Module({
  imports:[CurrencyPriceModule],
  providers: [RedisService],
  exports: [RedisService]
})
export class RedisModule {}
