import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { FutureAdlSettingService } from './future-adl-setting.service';
import { CreateFutureAdlSettingDto } from './dto/create-future-adl-setting.dto';
import { UpdateFutureAdlSettingDto } from './dto/update-future-adl-setting.dto';

@Controller('future-adl-setting')
export class FutureAdlSettingController {
  constructor(private readonly futureAdlSettingService: FutureAdlSettingService) {}

  @Post()
  create(@Body() createFutureAdlSettingDto: CreateFutureAdlSettingDto) {
    return this.futureAdlSettingService.create(createFutureAdlSettingDto);
  }

  @Get()
  findAll() {
    return this.futureAdlSettingService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.futureAdlSettingService.findOne(+id);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateFutureAdlSettingDto: UpdateFutureAdlSettingDto) {
    return this.futureAdlSettingService.update(+id, updateFutureAdlSettingDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.futureAdlSettingService.remove(+id);
  }
}
