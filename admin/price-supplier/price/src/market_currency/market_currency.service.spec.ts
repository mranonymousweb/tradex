import { Test, TestingModule } from '@nestjs/testing';
import { MarketCurrencyService } from './market_currency.service';

describe('MarketCurrencyService', () => {
  let service: MarketCurrencyService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MarketCurrencyService],
    }).compile();

    service = module.get<MarketCurrencyService>(MarketCurrencyService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
