import { Test, TestingModule } from '@nestjs/testing';
import { FutureOrderController } from './future-order.controller';
import { FutureOrderService } from './future-order.service';

describe('FutureOrderController', () => {
  let controller: FutureOrderController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [FutureOrderController],
      providers: [FutureOrderService],
    }).compile();

    controller = module.get<FutureOrderController>(FutureOrderController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
