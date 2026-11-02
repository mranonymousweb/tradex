import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { HydratedDocument, Types } from 'mongoose';
import { Base } from './base.schema';

export type BotDocument = HydratedDocument<Bot>;

@Schema({ timestamps: true })
export class Bot extends Base {

  @Prop({ required: true, type: Number })
  leastAmount: number;

  @Prop({ required: true, type: Number })
  mostAmount: number;

  @Prop({ required: true, type: Number })
  sellPercent: number;

  @Prop({ required: true, type: Number })
  buyPercent: number;

  @Prop({ required: true, type: Number })
  donePercent: number;

  @Prop({ required: true, type: Number, default: 1 })
  status: number;

  @Prop({ required: true, type: Number })
  secount: number;
}

export const BotSchema = SchemaFactory.createForClass(Bot);