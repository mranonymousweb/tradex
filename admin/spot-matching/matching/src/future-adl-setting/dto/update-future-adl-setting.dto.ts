import { PartialType } from '@nestjs/mapped-types';
import { CreateFutureAdlSettingDto } from './create-future-adl-setting.dto';

export class UpdateFutureAdlSettingDto extends PartialType(CreateFutureAdlSettingDto) {}
