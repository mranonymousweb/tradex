import { Module } from '@nestjs/common';
import { FutureOrderService } from './future-order.service';
import { FutureOrderController } from './future-order.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { FutureOrder } from './entities/future-order.entity';
import { UserAssetModule } from 'src/user-asset/user-asset.module';
import { UserTransactionModule } from 'src/user-transaction/user-transaction.module';
import { MarketCurrencyModule } from 'src/market-currency/market-currency.module';
import { Currency } from 'src/currency/entities/currency.entity';
import { CurrencyModule } from 'src/currency/currency.module';
import { FutureAdlSettingModule } from 'src/future-adl-setting/future-adl-setting.module';

@Module({
  imports: [TypeOrmModule.forFeature([FutureOrder]), UserAssetModule, UserTransactionModule, MarketCurrencyModule,
    CurrencyModule, FutureAdlSettingModule],
  controllers: [FutureOrderController],
  providers: [FutureOrderService],
  exports: [FutureOrderService],
})
export class FutureOrderModule {}
