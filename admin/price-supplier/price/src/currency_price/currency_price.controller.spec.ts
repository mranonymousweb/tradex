import { Test, TestingModule } from '@nestjs/testing';
import { CurrencyPriceController } from './currency_price.controller';
import { CurrenyPriceService } from './curreny_price.service';

describe('CurrenyPriceController', () => {
  let controller: CurrencyPriceController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [CurrencyPriceController],
      providers: [CurrenyPriceService],
    }).compile();

    controller = module.get<CurrencyPriceController>(CurrencyPriceController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
