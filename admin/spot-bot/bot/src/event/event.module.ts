import { forwardRef, Module } from '@nestjs/common';
import { EventGateway } from './event.gateway';
import { EventService } from './event.service';
import { AppModule } from 'src/app.module';

@Module({
  imports: [forwardRef(() => AppModule)],
  providers: [EventGateway, EventService],
  exports: [EventService],
})
export class EventModule {}