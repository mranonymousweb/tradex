import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { MarketCurrencyService } from './market-currency.service';
import { CreateMarketCurrencyDto } from './dto/create-market-currency.dto';
import { UpdateMarketCurrencyDto } from './dto/update-market-currency.dto';

@Controller('market-currency')
export class MarketCurrencyController {
  constructor(private readonly marketCurrencyService: MarketCurrencyService) {}

}
