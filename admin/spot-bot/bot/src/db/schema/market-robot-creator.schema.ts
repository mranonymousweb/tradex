import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document } from 'mongoose';

@Schema()
export class EmbeddedCurrency {
  @Prop()
  id: number;

  @Prop()
  symbol: string;
}

@Schema({ timestamps: true, collection: 'market_robot_creators' })
export class MarketRobotCreator extends Document {
  
  @Prop({ index: true })
  currency_id: string;

  @Prop({ type: String, length: 50, default: null })
  symbol: string;

  @Prop({ type: Number })
  leastAmount: number;

  @Prop({ type: Number })
  mostAmount: number;

  @Prop({ type: Number })
  sellPercent: number;

  @Prop({ type: Number })
  buyPercent: number;

  @Prop({ type: Number })
  donePercent: number;

  @Prop({ default: 1, index: true })
  status: number;

  @Prop({ index: true })
  secount: number;

  @Prop({ type: [EmbeddedCurrency] })
  currencies: EmbeddedCurrency[];

  @Prop({ default: 1, index: true })
  type: number;
}

export const MarketRobotCreatorSchema = SchemaFactory.createForClass(MarketRobotCreator);