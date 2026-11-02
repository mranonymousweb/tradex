import { Test, TestingModule } from '@nestjs/testing';
import { SupplierExchangeService } from './supplier-exchange.service';

describe('SupplierExchangeService', () => {
  let service: SupplierExchangeService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [SupplierExchangeService],
    }).compile();

    service = module.get<SupplierExchangeService>(SupplierExchangeService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});
