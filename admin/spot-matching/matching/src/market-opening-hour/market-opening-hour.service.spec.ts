import { Test, TestingModule } from '@nestjs/testing';
import { MarketOpeningHourService } from './market-opening-hour.service';

describe('MarketOpeningHourService', () => {
  let service: MarketOpeningHourService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [MarketOpeningHourService],
    }).compile();

    service = module.get<MarketOpeningHourService>(MarketOpeningHourService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
