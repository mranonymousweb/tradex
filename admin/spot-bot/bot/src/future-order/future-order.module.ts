import { Module } from '@nestjs/common';
import { FutureOrderService } from './future-order.service';
import { FutureOrderController } from './future-order.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { FutureOrder } from './entities/future-order.entity';

@Module({
  imports: [TypeOrmModule.forFeature([FutureOrder])],
  controllers: [FutureOrderController],
  providers: [FutureOrderService],
  exports: [FutureOrderService],
})
export class FutureOrderModule {}
