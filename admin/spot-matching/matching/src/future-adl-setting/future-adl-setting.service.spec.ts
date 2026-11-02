import { Test, TestingModule } from '@nestjs/testing';
import { FutureAdlSettingService } from './future-adl-setting.service';

describe('FutureAdlSettingService', () => {
  let service: FutureAdlSettingService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [FutureAdlSettingService],
    }).compile();

    service = module.get<FutureAdlSettingService>(FutureAdlSettingService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
