import { Test, TestingModule } from '@nestjs/testing';
import { SupplierExchangeController } from './supplier-exchange.controller';
import { SupplierExchangeService } from './supplier-exchange.service';

describe('SupplierExchangeController', () => {
  let controller: SupplierExchangeController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [SupplierExchangeController],
      providers: [SupplierExchangeService],
    }).compile();

    controller = module.get<SupplierExchangeController>(SupplierExchangeController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
