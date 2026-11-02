import { Module } from '@nestjs/common';
import { MarketCurrencyService } from './market-currency.service';
import { MarketCurrencyController } from './market-currency.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { MarketCurrency } from './entities/market-currency.entity';

@Module({
  imports: [TypeOrmModule.forFeature([MarketCurrency])],
  controllers: [MarketCurrencyController],
  providers: [MarketCurrencyService],
  exports: [MarketCurrencyService]
})
export class MarketCurrencyModule {}
