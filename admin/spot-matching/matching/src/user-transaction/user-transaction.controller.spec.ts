import { Test, TestingModule } from '@nestjs/testing';
import { UserTransactionController } from './user-transaction.controller';
import { UserTransactionService } from './user-transaction.service';

describe('UserTransactionController', () => {
  let controller: UserTransactionController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [UserTransactionController],
      providers: [UserTransactionService],
    }).compile();

    controller = module.get<UserTransactionController>(UserTransactionController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
