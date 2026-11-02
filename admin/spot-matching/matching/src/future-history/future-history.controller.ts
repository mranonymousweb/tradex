import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { FutureHistoryService } from './future-history.service';
import { CreateFutureHistoryDto } from './dto/create-future-history.dto';
import { UpdateFutureHistoryDto } from './dto/update-future-history.dto';

@Controller('future-history')
export class FutureHistoryController {
  constructor(private readonly futureHistoryService: FutureHistoryService) {}

  @Post()
  create(@Body() createFutureHistoryDto: CreateFutureHistoryDto) {
    return this.futureHistoryService.create(createFutureHistoryDto);
  }

  @Get()
  findAll() {
    return this.futureHistoryService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.futureHistoryService.findOne(+id);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateFutureHistoryDto: UpdateFutureHistoryDto) {
    return this.futureHistoryService.update(+id, updateFutureHistoryDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.futureHistoryService.remove(+id);
  }
}
