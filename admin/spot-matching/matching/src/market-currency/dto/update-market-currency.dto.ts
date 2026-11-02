import { PartialType } from '@nestjs/mapped-types';
import { CreateMarketCurrencyDto } from './create-market-currency.dto';

export class UpdateMarketCurrencyDto extends PartialType(CreateMarketCurrencyDto) {}
