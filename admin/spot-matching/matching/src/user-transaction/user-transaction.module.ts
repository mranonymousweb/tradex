import { Module } from '@nestjs/common';
import { UserTransactionService } from './user-transaction.service';
import { UserTransactionController } from './user-transaction.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { UserTransaction } from './entities/user-transaction.entity';

@Module({
  imports: [TypeOrmModule.forFeature([UserTransaction])],
  controllers: [UserTransactionController],
  providers: [UserTransactionService],
  exports: [UserTransactionService],
})
export class UserTransactionModule {}
