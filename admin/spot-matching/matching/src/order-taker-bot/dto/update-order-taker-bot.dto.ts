import { PartialType } from '@nestjs/mapped-types';
import { CreateOrderTakerBotDto } from './create-order-taker-bot.dto';

export class UpdateOrderTakerBotDto extends PartialType(CreateOrderTakerBotDto) {}
