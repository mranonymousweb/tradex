import { Inject, Injectable, OnModuleInit } from '@nestjs/common';
import { Kafka } from 'kafkajs';
import { KAFKA_CLIENT } from '../kafka/constant/kafka-client.constant';
import { RedisService } from '../redis/redis.service';

@Injectable()
export class KafkaConsumerService implements OnModuleInit {
  constructor(
    @Inject(KAFKA_CLIENT) private readonly kafka: Kafka,
    private readonly redisService: RedisService,
  ) {}

  async onModuleInit() {
    const consumer = this.kafka.consumer({
      groupId: 'bot-matching-group',
    });

    await consumer.connect();

    // ✅ subscribe dynamic (regex)
    // await consumer.subscribe({
    //   topic: /^(my-orders|bot-orders|real-orders|future-orders)(-.+)?$/,
    //   fromBeginning: true,
    // });

    // برای bot-orders (و شاخه‌های آن) با fromBeginning: false
  await consumer.subscribe({
    topic: "bot-orders",
    fromBeginning: false,
  });

    // برای bot-future-orders (و شاخه‌های آن) با fromBeginning: false
  await consumer.subscribe({
    topic: "future-bot-orders",
    fromBeginning: false,
  });

  

  // برای بقیه با fromBeginning: true
  await consumer.subscribe({
    topic: /^(my-orders|real-orders|future-real-orders|future-my-orders)(-.+)?$/,
    fromBeginning: true,
  });

  await consumer.run({
    autoCommit: false,
    eachBatch: async ({
      batch,
      resolveOffset,
      heartbeat,
      commitOffsetsIfNecessary,
      isRunning,
      isStale,
    }) => {
    const topic = batch.topic;          // ✅ گرفتن topic واقعی
    const partition = batch.partition;  // ✅ گرفتن partition واقعی

      for (const message of batch.messages) {
        if (!isRunning() || isStale()) break;
        if (!message.key || !message.value) continue;

    
         // ✅ تبدیل Buffer به JSON واقعی
          const parsedValue = JSON.parse(message.value.toString());
          // ساخت خروجی تمیز
        //  manage spot topics
          if ([ "bot-orders"].includes(topic)) {
            const payload = {
              pair: `${message.key}`,
              offset: message.offset,
              partition,
              timestamp: message.timestamp,
              data: parsedValue,
              from: "bot-orders"
            };

            await this.redisService.publish(
              'all:orders',
              JSON.stringify(payload),
            );
          }
          if (["real-orders"].includes(topic)) {
            const payload = {
              pair: `${message.key}`,
              offset: message.offset,
              partition,
              timestamp: message.timestamp,
              data: parsedValue,
              from: "real-orders"
            };
            await this.redisService.publish(
              'all:orders',
              JSON.stringify(payload),
            );
          }
          if (["my-orders"].includes(topic)) {

            const payload = {
              pair: `${message.key}`,
              offset: message.offset,
              partition,
              timestamp: message.timestamp,
              data: parsedValue,
            };

            await this.redisService.publish(
              'my:orders',
              JSON.stringify(payload),
            );
          }

          
          //  manage future topics
          if ([ "future-bot-orders"].includes(topic)) {
            const payload = {
              pair: `${message.key}`,
              offset: message.offset,
              partition,
              timestamp: message.timestamp,
              data: parsedValue,
              from: "bot-orders"
            };

            await this.redisService.publish(
              'future:all:orders',
              JSON.stringify(payload),
            );
          }
          if (["future-real-orders"].includes(topic)) {
            const payload = {
              pair: `${message.key}`,
              offset: message.offset,
              partition,
              timestamp: message.timestamp,
              data: parsedValue,
              from: "real-orders"
            };
            await this.redisService.publish(
              'future:all:orders',
              JSON.stringify(payload),
            );
          }
          if (["future-my-orders"].includes(topic)) {

            const payload = {
              pair: `${message.key}`,
              offset: message.offset,
              partition,
              timestamp: message.timestamp,
              data: parsedValue,
            };

            await this.redisService.publish(
              'future:my:orders',
              JSON.stringify(payload),
            );
          }
          
        resolveOffset(message.offset);
        await heartbeat();
      }

      await commitOffsetsIfNecessary();
    },
  });




    console.log('[Kafka] consumer started (motor-matching)');
  }
}
