import { Inject, Injectable, OnModuleInit } from "@nestjs/common";
import { Consumer, Kafka } from "kafkajs";
import { EngineRegistry } from "./engine/engine-registry";
import { KAFKA_CLIENT } from "src/kafka/constant/kafka-client.constant";
import { CoreService } from "src/core/core.service";
import { PendingOrderLoaderService } from "./engine/services/pending-orders.service";
import { FutureEngineRegistry } from "./future/future-engine-registry";
import { FutureOrderService } from "src/future-order/future-order.service";
import { MarketService } from "src/market/market.service";

@Injectable()
export class OrderDispatcherService implements OnModuleInit {
  private consumer: Consumer;
  private futureConsumer: Consumer;

  constructor(
     private readonly coreService: CoreService,
    @Inject(KAFKA_CLIENT) private readonly kafka: Kafka,
    private readonly engineRegistry: EngineRegistry,
    private readonly futureEngineRegistry: FutureEngineRegistry,
    private readonly pendingOrderLoaderService: PendingOrderLoaderService,
    private readonly futureOrderService: FutureOrderService,
    private readonly marketService: MarketService
  ) {}

  async onModuleInit() {
    // get spot pending orders
    // const markets = await this.coreService.getUnicMarkets()
    // markets.map(async pair => {
    //    await this.engineRegistry.initMarket(pair)
    // })
    // console.log("markets", markets)
    await this.initKafka();
    await this.initKafkaFuture();
  }

 private async initKafka() {

  const markets = await this.marketService.getUnicMarkets()
  markets.map(async pair => {
      await this.engineRegistry.initMarket(pair)
  })

  this.consumer = this.kafka.consumer({
    groupId: 'matching-engine-group',
  });

  await this.consumer.connect();
  await this.consumer.subscribe({ topic: 'orders' });

  await this.consumer.run({
    autoCommit: false,

    eachBatch: async ({
      batch,
      resolveOffset,
      heartbeat,
      commitOffsetsIfNecessary,
      isRunning,
      isStale,
    }) => {

      for (const message of batch.messages) {
        // console.log("message.key is : ", message.key)
        if (!isRunning() || isStale()) break;
        if (!message.key || !message.value) continue;

        try{
          const pair = message.key.toString();
          const payload = JSON.parse(message.value.toString());

          let engine = this.engineRegistry.getEngine(pair);

          if (!engine) {
            engine = await this.engineRegistry.initMarket(pair);
          }

          // console.log("payload", payload,)

          // const { data } = payload;
          // console.log("payload.type", payload.type,)
          // 👇 اینجا واقعا منتظر می‌مونیم پردازش تموم شه
          //  console.log("rtype payload", typeof payload)
          await engine.processOrder(payload.data, payload.type);

          resolveOffset(message.offset);
          await heartbeat();
        }catch (error) {
          // ❌ خطا در پردازش یک پیام
          console.error(`Error processing message with key ${message.key}:`, error);

          // ⚠️ انتخاب استراتژی:
          // گزینه ۱: offset را رد کن و ادامه بده (این پیام از دست می‌رود)
          resolveOffset(message.offset); 

          // گزینه ۲: offset را رد نکن (مصرف‌کننده مجدداً همان پیام را دریافت می‌کند - ممکن است باعث لوپ شود)
          // بهتر است پیام را به Dead Letter Topic بفرستید و سپس offset را رد کنید.
          
          await heartbeat();
        }
      }

      await commitOffsetsIfNecessary();
    },
  });
}


 private async initKafkaFuture() {

  const markets = await this.futureOrderService.getUnicMarkets()
  markets.map(async pair => {
      await this.futureEngineRegistry.initMarket(pair)
  })

  this.futureConsumer = this.kafka.consumer({
    groupId: 'future-matching-engine-group',
  });

  await this.futureConsumer.connect();
  await this.futureConsumer.subscribe({ topic: 'future-orders' });

  await this.futureConsumer.run({
    autoCommit: false,

    eachBatch: async ({
      batch,
      resolveOffset,
      heartbeat,
      commitOffsetsIfNecessary,
      isRunning,
      isStale,
    }) => {

      for (const message of batch.messages) {
        // console.log("message.key is : ", message.key)
        if (!isRunning() || isStale()) break;
        if (!message.key || !message.value) continue;

        try{
          const pair = message.key.toString();
          const payload = JSON.parse(message.value.toString());

          let engine = this.futureEngineRegistry.getEngine(pair);

          if (!engine) {
            engine = await this.futureEngineRegistry.initMarket(pair);
          }
          await engine.processOrder(payload.data, payload.type);

          resolveOffset(message.offset);
          await heartbeat();
        }catch (error) {
          // ❌ خطا در پردازش یک پیام
          console.error(`Error processing message with key ${message.key}:`, error);

          // ⚠️ انتخاب استراتژی:
          // گزینه ۱: offset را رد کن و ادامه بده (این پیام از دست می‌رود)
          resolveOffset(message.offset); 

          // گزینه ۲: offset را رد نکن (مصرف‌کننده مجدداً همان پیام را دریافت می‌کند - ممکن است باعث لوپ شود)
          // بهتر است پیام را به Dead Letter Topic بفرستید و سپس offset را رد کنید.
          
          await heartbeat();
        }
        
      }

      await commitOffsetsIfNecessary();
    },
  });
}

}
