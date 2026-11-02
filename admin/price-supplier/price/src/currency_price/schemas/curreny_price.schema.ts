import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { HydratedDocument } from 'mongoose';

export type CurrencyPriceDocument = HydratedDocument<CurrencyPrice>;

@Schema({
  collection: 'currency_prices',
  timeseries: {
    timeField: 'time',        // نام فیلد زمان (از کلاس شما)
    metaField: 'pair',        // نام فیلد برچسب برای گروه‌بندی (جفت‌ارز)
    granularity: 'seconds',   // چون داده‌ها هر چند ثانیه یک بار می‌آیند
  },
  expireAfterSeconds: 2592000 // اختیاری: حذف خودکار داده‌های بیش از ۳۰ روز
})
export class CurrencyPrice {
  // دیگر نیازی به تعریف صریح _id نیست
  // @Prop({ type: Types.ObjectId, auto: true })
  // _id: Types.ObjectId; // حذف شود

  @Prop({ required: true })
  pair: string;

  @Prop({ required: true })
  price: number;

  @Prop({ required: true })
  time: Date;
}

export const CurrencyPriceSchema = SchemaFactory.createForClass(CurrencyPrice);