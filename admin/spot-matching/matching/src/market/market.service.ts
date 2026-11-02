import { Injectable } from '@nestjs/common';
import { CreateMarketDto } from './dto/create-market.dto';
import { UpdateMarketDto } from './dto/update-market.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { Market } from './entities/market.entity';
import { In, Repository } from 'typeorm';
import { OrderStatusEnum } from 'src/common/enum/order.enum';

@Injectable()
export class MarketService {

  constructor(
      @InjectRepository(Market)
      private readonly marketRepository: Repository<Market>,
  ) {}

  create(createMarketDto: CreateMarketDto) {
    return 'This action adds a new market';
  }

  findAll() {
    return `This action returns all market`;
  }

  findOne(id: number) {
    return `This action returns a #${id} market`;
  }

  update(id: number, updateMarketDto: UpdateMarketDto) {
    return `This action updates a #${id} market`;
  }

  remove(id: number) {
    return `This action removes a #${id} market`;
  }

    async getMarketPendingOrders(marketPair: string) {
      return await this.marketRepository.find({
        where: { 
            pairs: marketPair, 
            status: In([ OrderStatusEnum.ACTIVE, OrderStatusEnum.IN_PROGRESS ]) 
        },
        order: { createdAt: 'DESC' },
      });
    }
  
    async getUnicMarkets() {
      const result =  await this.marketRepository
      .createQueryBuilder('m')
      .select('DISTINCT m.pairs', 'pairs')
      .where('m.status <> :status', 
        {status: OrderStatusEnum.DONE})
      .getRawMany()
  
      return result.map(r => r.pairs)
    }
}
