import { Test, TestingModule } from '@nestjs/testing';
import { FutureHistoryService } from './future-history.service';

describe('FutureHistoryService', () => {
  let service: FutureHistoryService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [FutureHistoryService],
    }).compile();

    service = module.get<FutureHistoryService>(FutureHistoryService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
