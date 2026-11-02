import { Test, TestingModule } from '@nestjs/testing';
import { MotorMatchingController } from './motor-matching.controller';

describe('MotorMatchingController', () => {
  let controller: MotorMatchingController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [MotorMatchingController],
    }).compile();

    controller = module.get<MotorMatchingController>(MotorMatchingController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
