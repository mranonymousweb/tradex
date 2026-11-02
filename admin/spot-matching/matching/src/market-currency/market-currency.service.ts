import { Injectable } from '@nestjs/common';
import { CreateMarketCurrencyDto } from './dto/create-market-currency.dto';
import { UpdateMarketCurrencyDto } from './dto/update-market-currency.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { MarketCurrency } from './entities/market-currency.entity';
import { Between, Repository } from 'typeorm';
import { UserAssetService } from 'src/user-asset/user-asset.service';
import { OrderStatusEnum } from 'src/common/enum/order.enum';

@Injectable()
export class MarketCurrencyService {
  constructor(
    @InjectRepository(MarketCurrency)
    private readonly marketCurrencyRepository: Repository<MarketCurrency>,
  ) {}

  async checkActivePair(pair: string) {
    return (await this.marketCurrencyRepository.find({where: {pairs: pair, status: Between(OrderStatusEnum.ACTIVE, OrderStatusEnum.IN_PROGRESS)}}) ? true: false);
  }

  async findByPair(pair: string, fields?: (keyof MarketCurrency)[]) {
    const options: any = { where: { pairs: pair } };
    if (fields) {
      options.select = fields;
    }
    return await this.marketCurrencyRepository.findOne(options);
  }


}
