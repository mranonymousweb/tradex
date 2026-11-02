import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { HydratedDocument, Types } from 'mongoose';
import { Base } from './base.schema';
import { LogLevelEnum } from 'src/common/enum/log-level.enum';
import { LogContextEnum } from 'src/common/enum/log-context.enum';

export type LogDocument = HydratedDocument<Log>;

@Schema({ timestamps: true })
export class Log extends Base {
  @Prop({ 
    type: String, 
    enum: Object.values(LogLevelEnum),
    default: LogLevelEnum.ERROR 
  })
  level: LogLevelEnum;

  @Prop({ required: true })
  message: string;

  @Prop()
  stack: string;

  @Prop({ type: String, enum: Object.values(LogContextEnum), required: true })
  context: LogContextEnum;

  @Prop({ type: Object })
  metadata: any;
}

export const LogSchema = SchemaFactory.createForClass(Log);