import { PartialType } from '@nestjs/mapped-types';
import { CreateCurrencyPriceDto } from './create-currency_price.dto';

export class UpdateCurrencyPriceDto extends PartialType(CreateCurrencyPriceDto) {}
