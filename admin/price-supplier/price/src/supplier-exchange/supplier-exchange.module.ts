import { Module } from '@nestjs/common';
import { SupplierExchangeService } from './supplier-exchange.service';
import { SupplierExchangeController } from './supplier-exchange.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { SupplierExchange } from './entities/supplier-exchange.entity';

@Module({
  imports: [TypeOrmModule.forFeature([SupplierExchange])],
  controllers: [SupplierExchangeController],
  providers: [SupplierExchangeService],
  exports: [SupplierExchangeService]
})
export class SupplierExchangeModule {}
