import { Module } from '@nestjs/common';
import { SanctumService } from './sanctum.service';


@Module({
    imports:[],
    providers: [SanctumService],
    exports: [SanctumService]
})
export class HelperModule {}
