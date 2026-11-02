import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { SupplierExchangeService } from './supplier-exchange.service';
import { CreateSupplierExchangeDto } from './dto/create-supplier-exchange.dto';
import { UpdateSupplierExchangeDto } from './dto/update-supplier-exchange.dto';

@Controller('supplier-exchange')
export class SupplierExchangeController {
  constructor(private readonly supplierExchangeService: SupplierExchangeService) {}

}
