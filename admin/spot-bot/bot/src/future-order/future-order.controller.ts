import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { FutureOrderService } from './future-order.service';
import { CreateFutureOrderDto } from './dto/create-future-order.dto';
import { UpdateFutureOrderDto } from './dto/update-future-order.dto';

@Controller('future-order')
export class FutureOrderController {
  constructor(private readonly futureOrderService: FutureOrderService) {}


}
