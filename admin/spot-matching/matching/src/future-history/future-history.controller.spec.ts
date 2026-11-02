import { Test, TestingModule } from '@nestjs/testing';
import { FutureHistoryController } from './future-history.controller';
import { FutureHistoryService } from './future-history.service';

describe('FutureHistoryController', () => {
  let controller: FutureHistoryController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [FutureHistoryController],
      providers: [FutureHistoryService],
    }).compile();

    controller = module.get<FutureHistoryController>(FutureHistoryController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
