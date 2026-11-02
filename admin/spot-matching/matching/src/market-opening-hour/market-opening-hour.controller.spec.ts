import { Test, TestingModule } from '@nestjs/testing';
import { MarketOpeningHourController } from './market-opening-hour.controller';
import { MarketOpeningHourService } from './market-opening-hour.service';

describe('MarketOpeningHourController', () => {
  let controller: MarketOpeningHourController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [MarketOpeningHourController],
      providers: [MarketOpeningHourService],
    }).compile();

    controller = module.get<MarketOpeningHourController>(MarketOpeningHourController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
