import { Test, TestingModule } from '@nestjs/testing';
import { UserAssetService } from './user-asset.service';

describe('UserAssetService', () => {
  let service: UserAssetService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [UserAssetService],
    }).compile();

    service = module.get<UserAssetService>(UserAssetService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
