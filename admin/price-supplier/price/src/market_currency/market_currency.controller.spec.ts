import { Test, TestingModule } from '@nestjs/testing';
import { MarketCurrencyController } from './market_currency.controller';
import { MarketCurrencyService } from './market_currency.service';

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
