import { Module } from '@nestjs/common';
import { FutureHistoryService } from './future-history.service';
import { FutureHistoryController } from './future-history.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { FutureHistory } from './entities/future-history.entity';

@Module({
  imports: [TypeOrmModule.forFeature([FutureHistory])],
  controllers: [FutureHistoryController],
  providers: [FutureHistoryService],
  exports: [FutureHistoryService],
})
export class FutureHistoryModule {}
