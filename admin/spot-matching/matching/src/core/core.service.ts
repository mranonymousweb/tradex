import {
  Inject,
  Injectable,
  OnModuleDestroy,
  OnModuleInit,
} from '@nestjs/common';
import { Kafka, Message, Producer } from 'kafkajs';
import { MarketCurrencyStatusEnum, MarketCurrencyTypeEnum } from 'src/common/enum/market-currency.enum';
import { OrderStatusEnum } from 'src/common/enum/order.enum';
import { ICoreMatchPayload } from 'src/common/interface/core-match-payload.interface';
import { IDropPayload } from 'src/common/interface/drop-payload.interface';
import { IOrderBook } from 'src/common/interface/order-book.interface';
import { IOrderPayload } from 'src/common/interface/order-payload.interface';
import { FutureOrderService } from 'src/future-order/future-order.service';
import { KAFKA_CLIENT } from 'src/kafka/constant/kafka-client.constant';
import { In, IsNull, Not } from 'typeorm';

@Injectable()
export class CoreService implements OnModuleInit, OnModuleDestroy {
  private producer: Producer;

  constructor(@Inject(KAFKA_CLIENT) kafkaClient: Kafka,

    private futureOrderService: FutureOrderService
  
  ) {
    this.producer = kafkaClient.producer();
  }

  async onModuleInit() {
    await this.producer.connect();
  }

  async onModuleDestroy() {
    await this.producer.disconnect();
  }

  // async getAllMarkets(): Promise<Pick<MarketCurrencyEntity, 'id' | 'pairs'>[]> {
  //   return await this.marketCurrencyRepository.find({ where: { status: MarketCurrencyStatusEnum.ACTIVE, type: MarketCurrencyTypeEnum.SOPT, mainCurrencyId: Not(IsNull()) }, select: { id: true, pairs: true }});
  // }




  async emitMatches(marketPair: string, payload: ICoreMatchPayload[]) {
    const topic = `match-${marketPair}`;
    const messages: Message[] = [{ value: JSON.stringify(payload) }];

    await this.producer.send({ topic, messages });
  }

  async emitDrops(marketPair: string, payload: IDropPayload) {
    const topic = `drop-${marketPair}`;
    const messages: Message[] = [{ value: JSON.stringify(payload) }];

    await this.producer.send({ topic, messages });
  }

  async emitOrderBook(marketPair: string, payload: IOrderBook) {
    const topic = `active-order-${marketPair}`;
    const messages: Message[] = [{ value: JSON.stringify(payload) }];

    await this.producer.send({ topic, messages });
  }

  async emitUserOrder(marketPair: string, payload: IOrderPayload[], userId: number) {
    const topic = `user-order-${marketPair}-${userId}`;
    const messages: Message[] = [{ value: JSON.stringify(payload) }];

    await this.producer.send({ topic, messages });
  }

   async saveTrade(trade: any): Promise<void> {
    // اینجا باید trade را در DB ذخیره کنی
    // مثلا با TypeORM:
    // await this.tradeRepository.save(trade);
    // console.log('Trade saved:', trade);
  }

  
}
