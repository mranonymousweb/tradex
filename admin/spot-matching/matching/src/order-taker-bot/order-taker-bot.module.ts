import { Module } from '@nestjs/common';
import { OrderTakerBotService } from './order-taker-bot.service';
import { OrderTakerBotController } from './order-taker-bot.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { OrderTakerBot } from './entities/order-taker-bot.entity';
import { RedisModule } from 'src/redis/redis.module';

@Module({
  imports: [TypeOrmModule.forFeature([OrderTakerBot]), RedisModule],
  controllers: [OrderTakerBotController],
  providers: [OrderTakerBotService],
  exports: [OrderTakerBotService]
})
export class OrderTakerBotModule {}
