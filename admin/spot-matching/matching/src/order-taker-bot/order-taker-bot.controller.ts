import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { OrderTakerBotService } from './order-taker-bot.service';
import { CreateOrderTakerBotDto } from './dto/create-order-taker-bot.dto';
import { UpdateOrderTakerBotDto } from './dto/update-order-taker-bot.dto';

@Controller('order-taker-bot')
export class OrderTakerBotController {
  constructor(private readonly orderTakerBotService: OrderTakerBotService) {}

  @Post()
  create(@Body() createOrderTakerBotDto: CreateOrderTakerBotDto) {
    return this.orderTakerBotService.create(createOrderTakerBotDto);
  }

  @Get()
  findAll() {
    return this.orderTakerBotService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.orderTakerBotService.findOne(+id);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateOrderTakerBotDto: UpdateOrderTakerBotDto) {
    return this.orderTakerBotService.update(+id, updateOrderTakerBotDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.orderTakerBotService.remove(+id);
  }
}
