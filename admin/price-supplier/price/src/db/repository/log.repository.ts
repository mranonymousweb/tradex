import { Model } from 'mongoose';
import { InjectModel } from '@nestjs/mongoose';
import { Log } from '../schema/log.schema';

export class BaseRepository<T> {
  constructor(public model: Model<T>) {}
}

export class LogRepository extends BaseRepository<Log> {
  constructor(@InjectModel(Log.name) model: Model<Log>) {
    super(model);
  }
}