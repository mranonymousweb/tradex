import { Model } from 'mongoose';
import { Bot } from '../schema/bot.schema';
import { InjectModel } from '@nestjs/mongoose';
import { MarketRobotCreator } from '../schema/market-robot-creator.schema';

export class BaseRepository<T> {
  constructor(public model: Model<T>) {}
}

export class BotRepository extends BaseRepository<Bot> {
  constructor(@InjectModel(Bot.name) model: Model<Bot>) {
    super(model);
  }
}

export class MarketRobotCreatorRepository extends BaseRepository<MarketRobotCreator> {
  constructor(@InjectModel(MarketRobotCreator.name) model: Model<MarketRobotCreator>) {
    super(model);
  }
}