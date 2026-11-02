import { PartialType } from '@nestjs/mapped-types';
import { CreateUserAssetDto } from './create-user-asset.dto';

export class UpdateUserAssetDto extends PartialType(CreateUserAssetDto) {}
