import { Injectable } from '@nestjs/common';
import { CreateMarketOpeningHourDto } from './dto/create-market-opening-hour.dto';
import { UpdateMarketOpeningHourDto } from './dto/update-market-opening-hour.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { MarketOpeningHour } from './entities/market-opening-hour.entity';
import { Repository } from 'typeorm';

@Injectable()
export class MarketOpeningHourService {
  constructor(
    @InjectRepository(MarketOpeningHour)
    private readonly marketOpeningHourRepository: Repository<MarketOpeningHour>
  ){}
  create(createMarketOpeningHourDto: CreateMarketOpeningHourDto) {
    return 'This action adds a new marketOpeningHour';
  }

  findAll() {
    return `This action returns all marketOpeningHour`;
  }

  findOne(id: number) {
    return `This action returns a #${id} marketOpeningHour`;
  }

  update(id: number, updateMarketOpeningHourDto: UpdateMarketOpeningHourDto) {
    return `This action updates a #${id} marketOpeningHour`;
  }

  remove(id: number) {
    return `This action removes a #${id} marketOpeningHour`;
  }

    async findByDay(day: string, fields?: (keyof MarketOpeningHour)[]) {
        const options: any = { where: { day: day } };
        if (fields) {
          options.select = fields;
        }
        return await this.marketOpeningHourRepository.findOne(options);
    }
}
