import {
  Inject,
  Injectable,
  OnModuleDestroy,
  OnModuleInit,
} from '@nestjs/common';
import { Kafka, Message, Producer } from 'kafkajs';
import { spliter } from 'src/common/constant/spliter.constant';
import { BotStatusEnum } from 'src/common/enum/bot-status.enum';
import { MarketCurrencyStatusEnum, MarketCurrencyTypeEnum } from 'src/common/enum/market-currency.enum';
import { IBotOrder, IBotOrderFuture } from 'src/common/interface/bot-order.interface';
import { IBotPayload } from 'src/common/interface/bot-payload.interface';
import { MarketCurrencyEntity } from 'src/db/entity/market-currency.entity';
import { MarketRobotCreatorRepository } from 'src/db/repository/bot.repository';
import { MarketCurrencyRepository } from 'src/db/repository/market-currency.repository';
import { KAFKA_CLIENT } from 'src/kafka/constant/kafka-client.constant';
import { In } from 'typeorm';

@Injectable()
export class CoreService implements OnModuleInit, OnModuleDestroy {
  private producer: Producer;
  
  constructor(@Inject(KAFKA_CLIENT) kafkaClient: Kafka, private marketCurrencyRepository: MarketCurrencyRepository, private marketRobotCreatorRepository: MarketRobotCreatorRepository) {
    this.producer = kafkaClient.producer();
  }

  async onModuleInit() {
    await this.producer.connect();
  }

  async onModuleDestroy() {
    await this.producer.disconnect();
  }

  async getAllMarkets(): Promise<Pick<MarketCurrencyEntity, 'id' | 'pairs'>[]> {
    return await this.marketCurrencyRepository.find({ where: { status: MarketCurrencyStatusEnum.ACTIVE, type: In([MarketCurrencyTypeEnum.SOPT, MarketCurrencyTypeEnum.FUTURE]) }, select: { id: true, pairs: true }});
  }

  async getSetting(mainSymbol: string, baseSymbol: string, type: number): Promise<IBotPayload> {
    try{
      const botSetting = await this.marketRobotCreatorRepository.model
      .findOne({
        symbol: baseSymbol,
        // 'currencies.symbol': mainSymbol,
        type: type,
        status: BotStatusEnum.ACTIVE
      })
      .sort({ createdAt: -1 })
      .exec();

      if (!botSetting) {
        return null;
      }


      return {
        _id: botSetting._id.toString(),
        buyPercent: botSetting.buyPercent,
        donePercent: botSetting.donePercent,
        leastAmount: botSetting.leastAmount,
        mostAmount: botSetting.mostAmount,
        secount: botSetting.secount,
        sellPercent: botSetting.sellPercent,
        status: botSetting.status,
        type: 1
      };
    } catch (error) {
      return null;
    }
  }



  async emitBotOrder(marketPair: string, orders: IBotOrder) {
    const topic = `bot-orders`;

    const payload = {
      timestamp: Date.now().toString(),
      last: orders?.done[0]?.price ? orders?.done[0]?.price : 0,
      buyers: orders.buyers,
      sellers: orders.sellers,
      done: orders.done
    };

    await this.producer.send({
      topic,
      messages: [
        { key: marketPair, value: JSON.stringify(payload) }
      ],
    });
  }

  async emitBotFutureOrder(marketPair: string, payload: any) {
    const topic = `future-bot-orders`;

    payload.last = payload?.done[0]?.price ? payload?.done[0]?.price : 0;
    payload.side = payload?.done?.[0]?.side ? payload?.done?.[0]?.side : null;

    const messages: Message[] = [{ key: marketPair, value: JSON.stringify(payload) }];

    await this.producer.send({ topic, messages });
  }
}
