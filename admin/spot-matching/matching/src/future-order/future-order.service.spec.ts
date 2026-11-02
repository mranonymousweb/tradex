import { Test, TestingModule } from '@nestjs/testing';
import { FutureOrderService } from './future-order.service';

describe('FutureOrderService', () => {
  let service: FutureOrderService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [FutureOrderService],
    }).compile();

    service = module.get<FutureOrderService>(FutureOrderService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
