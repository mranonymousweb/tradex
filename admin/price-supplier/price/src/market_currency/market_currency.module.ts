import { Module } from '@nestjs/common';
import { MarketCurrencyService } from './market_currency.service';
import { MarketCurrencyController } from './market_currency.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { MarketCurrency } from './entities/market_currency.entity';

@Module({
    imports: [TypeOrmModule.forFeature([MarketCurrency])],
    controllers: [MarketCurrencyController],
    providers: [MarketCurrencyService],
    exports: [MarketCurrencyService],
})
export class MarketCurrencyModule {}
