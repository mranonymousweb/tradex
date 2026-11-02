import { Test, TestingModule } from '@nestjs/testing';
import { OrderTakerBotService } from './order-taker-bot.service';

describe('OrderTakerBotService', () => {
  let service: OrderTakerBotService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [OrderTakerBotService],
    }).compile();

    service = module.get<OrderTakerBotService>(OrderTakerBotService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
