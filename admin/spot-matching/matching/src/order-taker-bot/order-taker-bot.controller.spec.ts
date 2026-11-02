import { Test, TestingModule } from '@nestjs/testing';
import { OrderTakerBotController } from './order-taker-bot.controller';
import { OrderTakerBotService } from './order-taker-bot.service';

describe('OrderTakerBotController', () => {
  let controller: OrderTakerBotController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [OrderTakerBotController],
      providers: [OrderTakerBotService],
    }).compile();

    controller = module.get<OrderTakerBotController>(OrderTakerBotController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
