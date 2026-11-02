import { PartialType } from '@nestjs/mapped-types';
import { CreateSupplierExchangeDto } from './create-supplier-exchange.dto';

export class UpdateSupplierExchangeDto extends PartialType(CreateSupplierExchangeDto) {}
