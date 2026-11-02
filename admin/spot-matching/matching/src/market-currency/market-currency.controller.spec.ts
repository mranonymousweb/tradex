import { Test, TestingModule } from '@nestjs/testing';
import { MarketCurrencyController } from './market-currency.controller';
import { MarketCurrencyService } from './market-currency.service';

describe('MarketCurrencyController', () => {
  let controller: MarketCurrencyController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [MarketCurrencyController],
      providers: [MarketCurrencyService],
    }).compile();

    controller = module.get<MarketCurrencyController>(MarketCurrencyController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
