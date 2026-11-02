import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { MarketCurrencyService } from './market_currency.service';
import { CreateMarketCurrencyDto } from './dto/create-market_currency.dto';
import { UpdateMarketCurrencyDto } from './dto/update-market_currency.dto';

@Controller('market-currency')
export class MarketCurrencyController {
  constructor(private readonly marketCurrencyService: MarketCurrencyService) {}

  @Post()
  create(@Body() createMarketCurrencyDto: CreateMarketCurrencyDto) {
    return this.marketCurrencyService.create(createMarketCurrencyDto);
  }

  @Get()
  findAll() {
    return this.marketCurrencyService.getActiveMarkets();
  }



  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.marketCurrencyService.findOne(+id);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateMarketCurrencyDto: UpdateMarketCurrencyDto) {
    return this.marketCurrencyService.update(+id, updateMarketCurrencyDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.marketCurrencyService.remove(+id);
  }
}
