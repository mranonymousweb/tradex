import { Test, TestingModule } from '@nestjs/testing';
import { CurrencyPriceService } from './currency_price.service';

describe('CurrenyPriceService', () => {
  let service: CurrencyPriceService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [CurrencyPriceService],
    }).compile();

    service = module.get<CurrencyPriceService>(CurrencyPriceService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
