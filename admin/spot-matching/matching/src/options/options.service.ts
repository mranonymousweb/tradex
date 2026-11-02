import { Injectable } from '@nestjs/common';
import { CreateOptionDto } from './dto/create-option.dto';
import { UpdateOptionDto } from './dto/update-option.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { In, Like, Repository } from 'typeorm';
import { Option } from './entities/option.entity';

/** فقط نرخ سود ثابت است؛ دوره‌ها از جدول options داینامیک خوانده می‌شوند */
export const FUTURE_PERIODIC_PROFIT_KEY = 'future_periodic_profit';

/** pattern کلیدهای دوره: future_*_period */
export const FUTURE_PERIOD_KEY_PATTERN = 'future_%_period';

export interface FuturePeriodicProfitConfig {
  profitRate: string;
  periods: {
    /** شناسه دوره — از کلید option استخراج می‌شود (مثل first, second, ...) */
    key: string;
    /** کلید کامل option */
    optionKey: string;
    time: string; // HH:mm
  }[];
}

@Injectable()
export class OptionsService {

  constructor(
    @InjectRepository(Option)
    private readonly optionRepository: Repository<Option>
  ){}
  create(createOpionDto: CreateOptionDto) {
    return 'This action adds a new opion';
  }

  findAll() {
    return `This action returns all opions`;
  }

  findOne(id: number) {
    return `This action returns a #${id} opion`;
  }

  update(id: number, updateOpionDto: UpdateOptionDto) {
    return `This action updates a #${id} opion`;
  }

  remove(id: number) {
    return `This action removes a #${id} opion`;
  }

  async getByKeys(keys: string[]): Promise<Record<string, string>> {
    const rows = await this.optionRepository.find({
      where: { key: In(keys) },
    });

    const map: Record<string, string> = {};
    for (const row of rows) {
      map[row.key] = row.value;
    }
    return map;
  }

  async getByKey(key: string): Promise<string | null> {
    const row = await this.optionRepository.findOne({ where: { key } });
    return row?.value ?? null;
  }

  /**
   * تمام کلیدهای دوره فیوچرز را داینامیک از options می‌خواند
   * مثال: future_first_period, future_second_period, future_custom_period, ...
   */
  async getFuturePeriodOptions(): Promise<Option[]> {
    return this.optionRepository.find({
      where: {
        key: Like(FUTURE_PERIOD_KEY_PATTERN),
      },
      order: {
        key: 'ASC',
      },
    });
  }

  async getFuturePeriodicProfitConfig(): Promise<FuturePeriodicProfitConfig | null> {
    const profitRate = await this.getByKey(FUTURE_PERIODIC_PROFIT_KEY);
    if (!profitRate) {
      return null;
    }

    const periodRows = await this.getFuturePeriodOptions();

    const periods: FuturePeriodicProfitConfig['periods'] = periodRows
      .filter((row) => !!row.value?.trim())
      .map((row) => ({
        key: this.extractPeriodKey(row.key),
        optionKey: row.key,
        time: row.value.trim(),
      }));

    if (periods.length === 0) {
      return null;
    }

    return {
      profitRate,
      periods,
    };
  }

  /**
   * future_first_period → first
   * future_second_period → second
   * future_evening_period → evening
   */
  private extractPeriodKey(optionKey: string): string {
    return optionKey
      .replace(/^future_/, '')
      .replace(/_period$/, '');
  }
}