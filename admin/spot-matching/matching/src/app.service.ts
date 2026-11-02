import { forwardRef, Inject, Injectable, OnModuleDestroy, OnModuleInit } from '@nestjs/common';
import { Consumer, Kafka } from 'kafkajs';
import { KAFKA_CLIENT } from './kafka/constant/kafka-client.constant';
import { CoreService } from './core/core.service';
import { IMarketPayload } from './common/interface/market-payload.interface';
import AsyncLock from 'async-lock';


@Injectable()
export class AppService {
  private lock: AsyncLock;
  private consumer: Consumer;


  constructor(
    @Inject(KAFKA_CLIENT) private kafkaClient: Kafka,
    private coreService: CoreService,
  ) {
    this.lock = new AsyncLock();

    // this.consumer = this.kafkaClient.consumer({
    //   groupId: 'match-engine-market',
    // });
  }

  // async onModuleInit() {
  //   await this.initMarkets();

  //   await this.consumer.connect();

  //   await this.consumeMarkets();
  // }

  // async onModuleDestroy() {
  //   await this.consumer.disconnect();

  //   await this.destroyMarketMatchHandlers();
  // }

  // async initMarkets() {
  //   const markets = await this.coreService.getAllMarkets();

  //   const batchSize = 10;
  //   const length = markets.length;

  //   for (let i = 0; i < length; i += batchSize) {
  //     const promises = [];
  //     const last = i + batchSize < length ? i + batchSize : length;

  //     for (let j = i; j < last; j++) {
  //       promises.push(this.handleMarket(markets[j]));
  //     }

  //     await Promise.all(promises);
  //   }
  // }

  // async consumeMarkets() {
  //   await this.consumer.subscribe({
  //     topic: 'market',
  //     fromBeginning: true,
  //   });

  //   await this.consumer.run({
  //     eachMessage: async ({ message }) => {
  //       const payload: IMarketPayload = JSON.parse(message.value.toString());

  //       await this.handleMarket(payload);
  //     },
  //   });
  // }

  // async handleMarket(payload: IMarketPayload) {
  //   if (this.marketMatchHandlers.has(payload.pairs)) {
  //     return;
  //   }

  //   const marketMatchHandler = new MatchHandler(
  //     payload.pairs,
  //     this.kafkaClient,
  //     this.coreService,
  //     this.lock,
  //     this.currencyPriceService,
  //     this.eventService,
  //   );

  //   await marketMatchHandler.init();

  //   this.marketMatchHandlers.set(payload.pairs, marketMatchHandler);
  // }

  // async destroyMarketMatchHandlers() {
  //   for (const marketMatchHandler of this.marketMatchHandlers.values()) {
  //     await marketMatchHandler.destroy();
  //   }
  // }

  // async getHello():Promise<string>  {
  //   return 'Hello World!';
  // }

  // getMarketMatchHandler(pair: string) {
  //   return this.marketMatchHandlers.get(pair);
  // }
}