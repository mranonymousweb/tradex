import { Test, TestingModule } from '@nestjs/testing';
import { FutureAdlSettingController } from './future-adl-setting.controller';
import { FutureAdlSettingService } from './future-adl-setting.service';

describe('FutureAdlSettingController', () => {
  let controller: FutureAdlSettingController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [FutureAdlSettingController],
      providers: [FutureAdlSettingService],
    }).compile();

    controller = module.get<FutureAdlSettingController>(FutureAdlSettingController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
