import { Injectable } from '@nestjs/common';
import { CreateMarketCurrencyDto } from './dto/create-market-currency.dto';
import { UpdateMarketCurrencyDto } from './dto/update-market-currency.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { MarketCurrency } from './entities/market-currency.entity';
import { Between, Repository } from 'typeorm';
import { OrderStatus } from 'src/db/entity/market.entity';
import { BotTypeEnum } from 'src/common/enum/bot-type.enum';

@Injectable()
export class MarketCurrencyService {
  constructor(
    @InjectRepository(MarketCurrency)
    private readonly marketCurrencyRepository: Repository<MarketCurrency>,
  ) {}

  async getSpotPairs(): Promise<string[]> {
    const result = await this.marketCurrencyRepository.find({
      select: {
        pairs: true,
      },
      where: {
        type: BotTypeEnum.SPOT,
      },
    });

    return result.map(item => item.pairs);
  }
  async getFuturePairs(): Promise<string[]> {
    const result = await this.marketCurrencyRepository.find({
      select: {
        pairs: true,
      },
      where: {
        type: BotTypeEnum.FUTURE,
      },
    });

    return result.map(item => item.pairs);
  }


}
