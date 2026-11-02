import { Module } from '@nestjs/common';
import { FutureAdlSettingService } from './future-adl-setting.service';
import { FutureAdlSettingController } from './future-adl-setting.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { FutureAdlSetting } from './entities/future-adl-setting.entity';

@Module({
  imports: [TypeOrmModule.forFeature([FutureAdlSetting])],
  controllers: [FutureAdlSettingController],
  providers: [FutureAdlSettingService],
  exports: [FutureAdlSettingService]
})
export class FutureAdlSettingModule {}
