import { Injectable } from '@nestjs/common';
import { CreateFutureHistoryDto } from './dto/create-future-history.dto';
import { UpdateFutureHistoryDto } from './dto/update-future-history.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { FutureHistory, FutureHistoryType } from './entities/future-history.entity';
import { Repository } from 'typeorm';

export interface CreatePeriodicProfitHistoryInput {
  orderId: number;
  fee: string;
  periodKey: string;
  periodTime: string;
  profitRate: string;
  date: string; // YYYY-MM-DD
}

@Injectable()
export class FutureHistoryService {

  constructor(
    @InjectRepository(FutureHistory)
    private readonly futureHistoryRepository: Repository<FutureHistory>
  ){}
  create(createFutureHistoryDto: CreateFutureHistoryDto) {
    return 'This action adds a new futureHistory';
  }

  findAll() {
    return `This action returns all futureHistory`;
  }

  findOne(id: number) {
    return `This action returns a #${id} futureHistory`;
  }

  update(id: number, updateFutureHistoryDto: UpdateFutureHistoryDto) {
    return `This action updates a #${id} futureHistory`;
  }

  remove(id: number) {
    return `This action removes a #${id} futureHistory`;
  }

  async createPeriodicProfitRecord(
    input: CreatePeriodicProfitHistoryInput,
  ): Promise<FutureHistory> {
    const now = new Date();
    const meta = JSON.stringify({
      period_key: input.periodKey,
      period_time: input.periodTime,
      profit_rate: input.profitRate,
      date: input.date,
    });

    const row = this.futureHistoryRepository.create({
      order_id: input.orderId,
      fee: input.fee,
      type: FutureHistoryType.PERIODIC_PROFIT,
      description: `periodic_profit:${input.periodKey}:${input.date}`,
      meta,
      created_at: now,
      updated_at: now,
    });

    return this.futureHistoryRepository.save(row);
  }

  async hasPeriodicProfitForSlot(
    orderId: number,
    periodKey: string,
    date: string,
  ): Promise<boolean> {
    const description = `periodic_profit:${periodKey}:${date}`;
    const count = await this.futureHistoryRepository.count({
      where: {
        order_id: orderId,
        type: FutureHistoryType.PERIODIC_PROFIT,
        description,
      },
    });
    return count > 0;
  }
}
