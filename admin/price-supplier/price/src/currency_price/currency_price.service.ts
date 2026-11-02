import { Injectable } from '@nestjs/common';
import { CreateCurrencyPriceDto } from './dto/create-currency_price.dto';
import { UpdateCurrencyPriceDto } from './dto/update-currency_price.dto';
import { InjectModel } from '@nestjs/mongoose';
import { CurrencyPrice, CurrencyPriceDocument } from './schemas/curreny_price.schema';
import { Model } from 'mongoose';

@Injectable()
export class CurrencyPriceService {
    constructor(
    @InjectModel(CurrencyPrice.name) private priceModel: Model<CurrencyPriceDocument>,
  ) {}

  //   // Single insert (for low-frequency or manual use)
  // async create(data: { price: number; time: number }): Promise<CurrencyPrice> {
  //   const created = new this.priceModel(data);
  //   return created.save();
  // }

  // Bulk insert (for buffered real-time data)
  async insertMany(dataArray: { pair: string, price: number; time: Date }[]): Promise<void> {
    if (dataArray.length === 0) return;
    await this.priceModel.insertMany(dataArray);
  }

}
