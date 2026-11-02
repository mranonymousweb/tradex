import { Injectable, NotFoundException } from '@nestjs/common';
import { CreateSupplierExchangeDto } from './dto/create-supplier-exchange.dto';
import { UpdateSupplierExchangeDto } from './dto/update-supplier-exchange.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { SupplierExchange } from './entities/supplier-exchange.entity';
import { Repository } from 'typeorm';

@Injectable()
export class SupplierExchangeService {

    constructor(
      @InjectRepository(SupplierExchange)
      private readonly supplierExchangeRepository: Repository<SupplierExchange>,
    ) {}
    async getActiveSupplier(): Promise<SupplierExchange> {
      const supplier = await this.supplierExchangeRepository
      .createQueryBuilder('se')
      .select(['se.id', 'se.name', 'se.price_supplier', 'se.supplier'])
      .where('se.name = se.price_supplier')
      .andWhere('se.price_supplier IS NOT NULL')
      .orderBy('se.id', 'ASC')
      .getOne();

      if (!supplier) {
        console.log('Active supplier not found')
        // throw new NotFoundException('Active supplier not found');
      }
      

      return supplier;
    }


}
