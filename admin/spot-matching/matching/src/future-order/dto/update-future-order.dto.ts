import { PartialType } from '@nestjs/mapped-types';
import { CreateFutureOrderDto } from './create-future-order.dto';

export class UpdateFutureOrderDto extends PartialType(CreateFutureOrderDto) {}
