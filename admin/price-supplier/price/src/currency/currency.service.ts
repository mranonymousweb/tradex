import { Injectable } from '@nestjs/common';
import { CreateCurrencyDto } from './dto/create-currency.dto';
import { UpdateCurrencyDto } from './dto/update-currency.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { Currency } from './entities/currency.entity';
import { Repository } from 'typeorm';
import { MarketCurrency } from 'src/market_currency/entities/market_currency.entity';

@Injectable()
export class CurrencyService {

  constructor(
    @InjectRepository(Currency)
    private readonly currencyRepository: Repository<Currency>,
  ) {}



  async getActiveCurrencies() {
    const symbols = await this.currencyRepository
      .createQueryBuilder('mc')
      .select('mc.symbol', 'symbol')
      .where('mc.status = :status', { status: 1 })
      .andWhere('mc.type = :type', { type: 1 })
      .orderBy('mc.id', 'ASC')
      .getRawMany(); // [{ symbol: 'BTC' }, { symbol: 'ETH' }, ...]

    return symbols.map(row => ({
      symbol: row.symbol,
      pairs: `${row.symbol}_USDT`,
    }));
  }







  
}
