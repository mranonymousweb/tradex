import { PartialType } from '@nestjs/mapped-types';
import { CreateFutureHistoryDto } from './create-future-history.dto';

export class UpdateFutureHistoryDto extends PartialType(CreateFutureHistoryDto) {}
