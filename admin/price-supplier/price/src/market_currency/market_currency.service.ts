import { Injectable } from '@nestjs/common';
import { CreateMarketCurrencyDto } from './dto/create-market_currency.dto';
import { UpdateMarketCurrencyDto } from './dto/update-market_currency.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { MarketCurrency } from './entities/market_currency.entity';
import { Repository } from 'typeorm';

@Injectable()
export class MarketCurrencyService {

  constructor(
    @InjectRepository(MarketCurrency)
    private readonly marketCurrencyRepository: Repository<MarketCurrency>,
  ) {}

  create(createMarketCurrencyDto: CreateMarketCurrencyDto) {
    return 'This action adds a new marketCurrency';
  }

  getActiveMarkets() {
    return this.marketCurrencyRepository
      .createQueryBuilder('mc')
      .where('mc.type = :type', { type: 1 })
      .andWhere('mc.status = :status', { status: 1 })
      .andWhere("mc.pairs LIKE :pair ESCAPE '\\'", { pair: '%\\_USDT' })
      .orderBy('mc.pairs', 'ASC')
      .addOrderBy('mc.id', 'ASC')
      .getMany();
  }

  getActiveFutureMarkets() {
    return this.marketCurrencyRepository
      .createQueryBuilder('mc')
      .where('mc.type = :type', { type: 2 })
      .andWhere('mc.status = :status', { status: 1 })
      .andWhere("mc.pairs LIKE :pair ESCAPE '\\'", { pair: '%\\_USDT' })
      .orderBy('mc.pairs', 'ASC')
      .addOrderBy('mc.id', 'ASC')
      .getMany();
  }

  findOne(id: number) {
    return `This action returns a #${id} marketCurrency`;
  }

  update(id: number, updateMarketCurrencyDto: UpdateMarketCurrencyDto) {
    return `This action updates a #${id} marketCurrency`;
  }

  remove(id: number) {
    return `This action removes a #${id} marketCurrency`;
  }


  async getActiveSpotCurrencies(): Promise<string[]> {
    const rows = await this.marketCurrencyRepository
      .createQueryBuilder('mc')
      .select('DISTINCT mc.pairs', 'pairs')
      .where('mc.status = :status', { status: 1 })
      .andWhere('mc.type = :type', { type: 1 })
      .andWhere('mc.base_currency_symbol IS NOT NULL')
      .andWhere('mc.base_currency_symbol <> :excludedSymbol', {
        excludedSymbol: 'USDT',
      })
      .andWhere('mc.pairs IS NOT NULL')
      .getRawMany<{ pairs: string }>();

    return rows.map((row) => row.pairs);
  }

  async getActiveFutureCurrencies(): Promise<string[]> {
    const rows = await this.marketCurrencyRepository
      .createQueryBuilder('mc')
      .select('DISTINCT mc.pairs', 'pairs')
      .where('mc.status = :status', { status: 1 })
      .andWhere('mc.type = :type', { type: 2 })
      .andWhere('mc.base_currency_symbol IS NOT NULL')
      .andWhere('mc.base_currency_symbol <> :excludedSymbol', {
        excludedSymbol: 'USDT',
      })
      .andWhere('mc.pairs IS NOT NULL')
      .getRawMany<{ pairs: string }>();

    return rows.map((row) => row.pairs);
  }


  
}
