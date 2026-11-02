import { PartialType } from '@nestjs/mapped-types';
import { CreateMarketOpeningHourDto } from './create-market-opening-hour.dto';

export class UpdateMarketOpeningHourDto extends PartialType(CreateMarketOpeningHourDto) {}
