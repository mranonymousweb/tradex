import { Module } from '@nestjs/common';
import { MarketOpeningHourService } from './market-opening-hour.service';
import { MarketOpeningHourController } from './market-opening-hour.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { MarketOpeningHour } from './entities/market-opening-hour.entity';

@Module({
  imports: [TypeOrmModule.forFeature([MarketOpeningHour])],
  controllers: [MarketOpeningHourController],
  providers: [MarketOpeningHourService],
  exports: [MarketOpeningHourService]
})
export class MarketOpeningHourModule {}
