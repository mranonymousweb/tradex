import { Module } from '@nestjs/common';
import { UserAssetService } from './user-asset.service';
import { UserAssetController } from './user-asset.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { UserAsset } from './entities/user-asset.entity';

@Module({
  imports: [TypeOrmModule.forFeature([UserAsset])],
  controllers: [UserAssetController],
  providers: [UserAssetService],
  exports: [UserAssetService],
})
export class UserAssetModule {}
