import { Module } from '@nestjs/common';
import { CurrencyPriceService } from './currency_price.service';
import { CurrencyPriceController } from './currency_price.controller';
import { MongooseModule } from '@nestjs/mongoose';
import { CurrencyPrice, CurrencyPriceSchema } from './schemas/curreny_price.schema';

@Module({
    imports: [
    MongooseModule.forFeature([{ name: CurrencyPrice.name, schema: CurrencyPriceSchema }]),
  ],
  controllers: [CurrencyPriceController],
  providers: [CurrencyPriceService],
  exports: [CurrencyPriceService]
})
export class CurrencyPriceModule {}
