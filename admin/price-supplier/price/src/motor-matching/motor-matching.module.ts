import { Module } from '@nestjs/common';
import { MotorMatchingController } from './motor-matching.controller';
import { RedisModule } from 'src/redis/redis.module';
import { KafkaConsumerService } from './kafka-consumer.service';
import { KafkaModule } from 'src/kafka/kafka.module';

@Module({
  controllers: [MotorMatchingController],
  imports: [RedisModule, KafkaModule],
  providers: [KafkaConsumerService]
})
export class MotorMatchingModule {}
