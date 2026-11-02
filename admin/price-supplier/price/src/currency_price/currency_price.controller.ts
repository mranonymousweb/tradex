import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { CurrencyPriceService } from './currency_price.service';
import { CreateCurrencyPriceDto } from './dto/create-currency_price.dto';
import { UpdateCurrencyPriceDto } from './dto/update-currency_price.dto';

@Controller('currency-price')
export class CurrencyPriceController {
  constructor(private readonly currencyPriceService: CurrencyPriceService) {}


}
