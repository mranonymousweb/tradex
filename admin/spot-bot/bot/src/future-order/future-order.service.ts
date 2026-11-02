import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { FutureOrder, FutureOrderSide, FutureOrderStatus } from './entities/future-order.entity';
import { Repository, In, EntityManager } from 'typeorm';
import Decimal from 'decimal.js';

@Injectable()
export class FutureOrderService {
  constructor(
    @InjectRepository(FutureOrder)
    private readonly futureOrderRepository: Repository<FutureOrder>,
  ) {}

  
  async getPairs(): Promise<string[]> {
    const result = await this.futureOrderRepository.find({
      select: {
        pairs: true,
      },
    });

    return result.map(item => item.pairs);
  }
}
