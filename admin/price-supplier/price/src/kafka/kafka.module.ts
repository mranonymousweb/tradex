import { Module } from '@nestjs/common';
import { KAFKA_CLIENT } from './constant/kafka-client.constant';
import { ConfigService, ConfigType } from '@nestjs/config';
import appEnvConfig from 'src/config/app-env.config';
import { Kafka } from 'kafkajs';

@Module({
  providers: [
    {
      provide: KAFKA_CLIENT,
      useFactory: (configService: ConfigService<ConfigType<typeof appEnvConfig>>) => {
        const kafkaEnvConfig = configService.get('kafka', {infer: true});

        return new Kafka({
          brokers: kafkaEnvConfig?.broker ? [kafkaEnvConfig.broker] : ['localhost:9092'],
          // brokers: ['localhost:9092'],
          clientId: kafkaEnvConfig?.clientId,
          retry: {
            initialRetryTime: 300,    // 300ms
            maxRetryTime: 30000,      // 30 ثانیه
            retries: 10               // 10 بار تلاش مجدد برای عملیات تولید/مصرف
          },
          connectionTimeout: 30000,
          requestTimeout: 40000,
        });
      },
      inject: [ConfigService]
    }
  ],
  exports: [KAFKA_CLIENT]
})
export class KafkaModule {}
