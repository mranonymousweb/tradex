import { Injectable } from '@nestjs/common';
import { CreateOrderTakerBotDto } from './dto/create-order-taker-bot.dto';
import { UpdateOrderTakerBotDto } from './dto/update-order-taker-bot.dto';
import { InjectRepository } from '@nestjs/typeorm';
import {
  OrderTakerBot,
  OrderTakerBotStatus,
} from './entities/order-taker-bot.entity';
import { Repository } from 'typeorm';
import { RedisService } from 'src/redis/redis.service';

@Injectable()
export class OrderTakerBotService {
  private readonly CACHE_TTL_SECONDS = 60;
  private readonly CACHE_PREFIX = 'order-taker-bot:active:';

  constructor(
    @InjectRepository(OrderTakerBot)
    private readonly orderTakerBotRepository: Repository<OrderTakerBot>,
    private readonly redisService: RedisService,
  ){}
  create(createOrderTakerBotDto: CreateOrderTakerBotDto) {
    return 'This action adds a new orderTakerBot';
  }

  findAll() {
    return `This action returns all orderTakerBot`;
  }

  findOne(id: number) {
    return `This action returns a #${id} orderTakerBot`;
  }

  /**
   * پیدا کردن bot فعال با symbol پایه بازار (مثل USDT).
   *
   * کش Redis با TTL یک دقیقه:
   * - اگر در کش بود → همان را برمی‌گرداند (بدون DB)
   * - اگر نبود → از DB می‌خواند و ۱ دقیقه در کش می‌گذارد
   */
  async findActiveBySymbol(symbol: string): Promise<OrderTakerBot | null> {
    
    const cacheKey = `${this.CACHE_PREFIX}${symbol}`;
    // 1) کش hit
    const cached = await this.redisService.get(cacheKey);

    if (cached !== null && cached !== undefined) {
      console.log("findActiveBySymbol not found", cacheKey )
      return cached === 'null' ? null : (JSON.parse(cached) as OrderTakerBot);
    }

    // 2) کش miss → DB
    const bot = await this.orderTakerBotRepository.findOne({
      where: {
        symbol,
        status: OrderTakerBotStatus.ACTIVE,
      },
    });


    // 3) ذخیره در کش برای ۶۰ ثانیه
    await this.redisService.setex(
      cacheKey,
      this.CACHE_TTL_SECONDS,
      bot ? JSON.stringify(bot) : 'null',
    );

    return bot;
  }

  update(id: number, updateOrderTakerBotDto: UpdateOrderTakerBotDto) {
    return `This action updates a #${id} orderTakerBot`;
  }

  remove(id: number) {
    return `This action removes a #${id} orderTakerBot`;
  }
}
