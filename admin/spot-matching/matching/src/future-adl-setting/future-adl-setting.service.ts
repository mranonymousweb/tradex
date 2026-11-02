import { Injectable } from '@nestjs/common';
import { CreateFutureAdlSettingDto } from './dto/create-future-adl-setting.dto';
import { UpdateFutureAdlSettingDto } from './dto/update-future-adl-setting.dto';
import { FutureAdlSetting } from './entities/future-adl-setting.entity';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';

@Injectable()
export class FutureAdlSettingService {
  constructor(
    @InjectRepository(FutureAdlSetting)
    private readonly futureAdlSettingRepository: Repository<FutureAdlSetting>,
  ) {}

  create(createFutureAdlSettingDto: CreateFutureAdlSettingDto) {
    return 'This action adds a new futureAdlSetting';
  }

  findAll() {
    return `This action returns all futureAdlSetting`;
  }

  findOne(id: number) {
    return `This action returns a #${id} futureAdlSetting`;
  }



  remove(id: number) {
    return `This action removes a #${id} futureAdlSetting`;
  }

  async findByBaseCurrency(base_currency_id: number, fields?: (keyof FutureAdlSetting)[]) {
      const options: any = { where: { baseCurrencyId: base_currency_id } };
      if (fields) {
        options.select = fields;
      }
      return await this.futureAdlSettingRepository.findOne(options);
  }

    /**
     * آپدیت با ID (روش پیشنهادی)
     * - entity را می‌خواند، merge می‌کند، save می‌کند
     * - خروجی: آبجکت آپدیت‌شده
     */
  async update(id: number, updateFutureAdlSettingDto: UpdateFutureAdlSettingDto): Promise<FutureAdlSetting> {
        const market = await this.futureAdlSettingRepository.preload({
          id: id,
          ...updateFutureAdlSettingDto,
        });
        
        return await this.futureAdlSettingRepository.save(market);
  }


}
