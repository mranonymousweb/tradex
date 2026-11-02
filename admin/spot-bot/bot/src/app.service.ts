import { forwardRef, Inject, Injectable, OnModuleDestroy, OnModuleInit } from "@nestjs/common";
import AsyncLock from "async-lock";
import { Consumer, Kafka } from "kafkajs";
import { BotHandler } from "./common/handler/bot.handler";
import { KAFKA_CLIENT } from "./kafka/constant/kafka-client.constant";
import { CoreService } from "./core/core.service";
import { CurrencyPriceService } from "./currency-price/currency-price.service";
import { EventService } from "./event/event.service";
import { IBotPayload } from "./common/interface/bot-payload.interface";
import { BotTypeEnum } from "./common/enum/bot-type.enum";
import { IMarketPayload } from "./common/interface/market-payload.interface";
import { FutureBotHandler } from "./common/handler/future-bot.handler";
import { LogRepository } from "./db/repository/log.repository";
import { LogContextEnum } from "./common/enum/log-context.enum";
import { LogLevelEnum } from "./common/enum/log-level.enum";
import { MarketCurrencyService } from "./market-currency/market-currency.service";
import { FutureOrderService } from "./future-order/future-order.service";
import { spliter } from "./common/constant/spliter.constant";

@Injectable()
export class AppService implements OnModuleInit, OnModuleDestroy {
  private lock: AsyncLock;
  private consumer: Consumer;
  private botConsumer: Consumer;

  private botHandlers: Map<string, BotHandler> = new Map();
  private futureBotHandlers: Map<string, FutureBotHandler> = new Map();

  private readonly BOT_CONFIG = 'bot-config';

  constructor(
    @Inject(KAFKA_CLIENT) private kafkaClient: Kafka,
    private coreService: CoreService,
    private currencyPriceService: CurrencyPriceService,
    @Inject(forwardRef(() => EventService)) private eventService: EventService,
    private logRepository: LogRepository,
    private marketCurrencyService: MarketCurrencyService,
  ) {
    this.lock = new AsyncLock();

    this.consumer = this.kafkaClient.consumer({ groupId: 'bot-market' });

    this.botConsumer = this.kafkaClient.consumer({
      groupId: 'bot-spot-central',
    });
  }

  async onModuleInit() {
    console.log("onModuleInit")
    await this.initSpotMarkets();
    await this.initFutureMarkets();

    await this.consumer.connect();
    await this.botConsumer.connect();

    await this.consumeMarkets();
    await this.consumeBotsCentral();
  }

  async onModuleDestroy() {
    await this.consumer.disconnect();
    await this.botConsumer.disconnect();
    await this.destroySpotBotHandlers();
    await this.destroyFutureBotHandlers();
  }


  async initSpotMarkets() {
    // const markets = await this.coreService.getAllMarkets();
    const pairs = await this.marketCurrencyService.getSpotPairs();
    const batchSize = 10;
    const length = pairs.length;

    for (let i = 0; i < length; i += batchSize) {
      const promises = pairs.slice(i, i + batchSize).map(pair => this.handleSpotMarket(pair));
      await Promise.all(promises);
    }
  }

  async initFutureMarkets() {
    const markets = await this.marketCurrencyService.getFuturePairs();
    const batchSize = 10;
    const length = markets.length;

    for (let i = 0; i < length; i += batchSize) {
      const promises = markets.slice(i, i + batchSize).map(pair => this.handleFutureMarket(pair));
      await Promise.all(promises);
    }
  }

  async consumeMarkets() {
    await this.consumer.subscribe({ topic: 'market', fromBeginning: true });

    await this.consumer.run({
      eachMessage: async ({ message }) => {
        const payload: IMarketPayload = JSON.parse(message.value.toString());
        console.log("consumeMarkets 1", payload)
        if(payload.type == BotTypeEnum.SPOT)
          await this.handleSpotMarket(payload.pairs);

        if(payload.type == BotTypeEnum.FUTURE)
          console.log("consumeMarkets", payload)
          await this.handleFutureMarket(payload.pairs);
      },
    });
  }

  async handleSpotMarket(pairs: string) {

    // manage spot
    if (this.botHandlers.has(pairs)) return;

    const botHandler = new BotHandler(
      pairs,
      this.lock,
      this.coreService,
      this.currencyPriceService,
      this.eventService,
    );

    await botHandler.init();
    this.botHandlers.set(pairs, botHandler);

  }

  async handleFutureMarket(pairs: string) {
    // manage futures
    if (this.futureBotHandlers.has(pairs)) return;

    const futureBotHandler = new FutureBotHandler(
      pairs,
      this.lock,
      this.coreService,
      this.currencyPriceService,
      this.eventService,
    );

    await futureBotHandler.init();
    this.futureBotHandlers.set(pairs, futureBotHandler);
  }

  private async consumeBotsCentral() {
    await this.botConsumer.subscribe({
      topic: this.BOT_CONFIG,
      fromBeginning: false,
    });

    await this.botConsumer.run({
      eachMessage: async ({ message }) => {
        try {


          const key = message?.key?.toString();
          const payload: IBotPayload = JSON.parse(message.value.toString());

          if (!payload || !key) return;

          const marketBase = payload.symbol ?? key;

          if(payload?.type === BotTypeEnum.FUTURE) {
            await this.destroyFutureBotHandlers(marketBase)

             for (const element of payload.currencies) {
              console.log("element",  element)
              const futureBotHandler = this.futureBotHandlers.get(element);

              if (futureBotHandler) {
                await futureBotHandler.handleBot(payload);
              } else {
                await this.handleFutureMarket(element) ;
                console.warn(`No FutureBotHandler found for pair: ${element}`);
              }
            }
            // const futureBotHandler = this.futureBotHandlers.get(key);
            
            // if (futureBotHandler) {
            //   await futureBotHandler.handleBot(payload);
            // } else {
            //   console.warn(`No FutureBotHandler found for pair: ${key}`);
            // }

            return;
          }

           await this.destroySpotBotHandlers(marketBase)

          for (const element of payload.currencies) {
              console.log("element",  element)
              const botHandler = this.botHandlers.get(element);

              if (botHandler) {
                await botHandler.handleBot(payload);
              } else {
                await this.handleSpotMarket(element) ;
                console.warn(`No botHandler found for pair: ${element}`);
              }
            }

          // if (botHandler) {
          //   await botHandler.handleBot(payload);
          // } else {
          //   console.warn(`No BotHandler found for pair: ${key}`);
          // }
        } catch (error) {
          console.error('Error processing bot-spot message:', error);
          await this.logRepository.model.insertOne({
            context: LogContextEnum.BOT,
            level: LogLevelEnum.ERROR,
            message: "Error processing bot-spot message",
            stack: error.toString()
          });
        }
      },
    });
  }

  // async destroyBotHandlers() {
  //   for (const handler of this.botHandlers.values()) {
  //     await handler.destroy();
  //   }
  //   this.botHandlers.clear();

  //   for (const futureHandler of this.futureBotHandlers.values()) {
  //     await futureHandler.destroy();
  //   }
  //   this.futureBotHandlers.clear();
  // }

  async destroySpotBotHandlers(marketBase?: string) {
    const normalizedBase = marketBase?.toUpperCase().trim();

    for (const [pair, handler] of this.botHandlers.entries()) {
      if (normalizedBase && !this.belongsToMarketBase(pair, normalizedBase)) {
        continue;
      }

      await handler.destroy();
      this.botHandlers.delete(pair);
    }
  }

  async destroyFutureBotHandlers(marketBase?: string) {
    const normalizedBase = marketBase?.toUpperCase().trim();

    for (const [pair, handler] of this.futureBotHandlers.entries()) {
      if (normalizedBase && !this.belongsToMarketBase(pair, normalizedBase)) {
        continue;
      }

      await handler.destroy();
      this.futureBotHandlers.delete(pair);
    }
  }

  private belongsToMarketBase(pair: string, marketBase: string): boolean {
    const parts = pair.split(spliter);
    const base = (parts[1] || '').toUpperCase().trim();
    return base === marketBase;
  }

  async getHello(): Promise<string> {
    return 'Hello World!';
  }

  getBotHandler(pair: string) {
    return this.botHandlers.get(pair);
  }
}