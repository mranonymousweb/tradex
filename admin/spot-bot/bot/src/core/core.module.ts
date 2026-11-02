import { Module } from '@nestjs/common';
import { CoreService } from './core.service';
import { DbModule } from 'src/db/db.module';
import { KafkaModule } from 'src/kafka/kafka.module';

@Module({
  imports: [DbModule, KafkaModule],
  providers: [CoreService],
  exports: [CoreService]
})
export class CoreModule {}
