import { Test, TestingModule } from '@nestjs/testing';
import { UserAssetController } from './user-asset.controller';
import { UserAssetService } from './user-asset.service';

describe('UserAssetController', () => {
  let controller: UserAssetController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [UserAssetController],
      providers: [UserAssetService],
    }).compile();

    controller = module.get<UserAssetController>(UserAssetController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
