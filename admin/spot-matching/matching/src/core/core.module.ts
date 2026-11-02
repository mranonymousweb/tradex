import { Module } from '@nestjs/common';
import { CoreService } from './core.service';
import { KafkaModule } from 'src/kafka/kafka.module';
import { FutureOrderModule } from 'src/future-order/future-order.module';

@Module({
  imports: [KafkaModule, FutureOrderModule],
  providers: [CoreService],
  exports: [CoreService]
})
export class CoreModule {}
