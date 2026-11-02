import { Module } from '@nestjs/common';
import { CurrencyPriceService } from './currency-price.service';
import { RedisModule } from 'src/redis/redis.module';

@Module({
  imports: [RedisModule],
  providers: [CurrencyPriceService],
  exports: [CurrencyPriceService],
})
export class CurrencyPriceModule {}
