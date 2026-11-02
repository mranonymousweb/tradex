import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { MarketOpeningHourService } from './market-opening-hour.service';
import { CreateMarketOpeningHourDto } from './dto/create-market-opening-hour.dto';
import { UpdateMarketOpeningHourDto } from './dto/update-market-opening-hour.dto';

@Controller('market-opening-hour')
export class MarketOpeningHourController {
  constructor(private readonly marketOpeningHourService: MarketOpeningHourService) {}

  @Post()
  create(@Body() createMarketOpeningHourDto: CreateMarketOpeningHourDto) {
    return this.marketOpeningHourService.create(createMarketOpeningHourDto);
  }

  @Get()
  findAll() {
    return this.marketOpeningHourService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.marketOpeningHourService.findOne(+id);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateMarketOpeningHourDto: UpdateMarketOpeningHourDto) {
    return this.marketOpeningHourService.update(+id, updateMarketOpeningHourDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.marketOpeningHourService.remove(+id);
  }
}
