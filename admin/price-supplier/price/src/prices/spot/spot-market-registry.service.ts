import { Inject, Injectable, Logger, OnModuleInit } from '@nestjs/common';
import { Kafka } from 'kafkajs';
import { BotTypeEnum } from 'src/common/enum/bot-type.enum';
import { IMarketPayload } from 'src/common/interface/market-payload.interface';
import { KAFKA_CLIENT } from 'src/kafka/constant/kafka-client.constant';
import { MarketCurrencyService } from 'src/market_currency/market_currency.service';

@Injectable()
export class SpotMarketRegistryService implements OnModuleInit {
  private readonly logger = new Logger(SpotMarketRegistryService.name);
  private readonly activeSpotPairs = new Set<string>();
  private readonly activeFuturePairs = new Set<string>();

  constructor(
    @Inject(KAFKA_CLIENT) private readonly kafka: Kafka,
    private readonly marketCurrencyService: MarketCurrencyService,
  ) {}

  async onModuleInit() {
    await this.loadFromDatabase();
    await this.consumeMarkets();
  }

  private async loadFromDatabase() {
    const [spotPairs, futurePairs] = await Promise.all([
      this.marketCurrencyService.getActiveSpotCurrencies(),
      this.marketCurrencyService.getActiveFutureCurrencies(),
    ]);

    for (const pair of spotPairs) {
      this.addSpotPair(pair);
    }

    for (const pair of futurePairs) {
      this.addFuturePair(pair);
    }

    this.logger.log(
      `Loaded ${this.activeSpotPairs.size} spot and ${this.activeFuturePairs.size} future pairs from database`,
    );
  }

  private async consumeMarkets() {
    const consumer = this.kafka.consumer({
      groupId: 'price-market-group',
    });

    await consumer.connect();
    await consumer.subscribe({ topic: 'market', fromBeginning: true });

    await consumer.run({
      eachMessage: async ({ message }) => {
        if (!message.value) return;

        try {
          const payload: IMarketPayload = JSON.parse(message.value.toString());
          console.log("consumeMarkets market ", payload)

          if (payload.type === BotTypeEnum.SPOT) {
            console.log("consumeMarkets market spot ", payload.pairs)
            this.addSpotPair(payload.pairs);
            return;
          }

          if (payload.type === BotTypeEnum.FUTURE) {
            this.addFuturePair(payload.pairs);
          }
        } catch (error: any) {
          this.logger.error(`Failed to consume market message: ${error?.message || error}`);
        }
      },
    });

    this.logger.log('[Kafka] market consumer started');
  }

  addSpotPair(pair: string) {
    const normalized = this.normalizePair(pair);
     console.log("consumeMarkets market spot normalized", normalized)
    if (!normalized) return;

    if (this.activeSpotPairs.has(normalized)) return;

    this.activeSpotPairs.add(normalized);
    this.logger.log(`Added spot pair: ${normalized}`);
  }

  addFuturePair(pair: string) {
    const normalized = this.normalizePair(pair);
    if (!normalized) return;

    if (this.activeFuturePairs.has(normalized)) return;

    this.activeFuturePairs.add(normalized);
    this.logger.log(`Added future pair: ${normalized}`);
  }

  getActiveSpotPairs(): string[] {
    return [...this.activeSpotPairs];
  }

  getActiveFuturePairs(): string[] {
    return [...this.activeFuturePairs];
  }

  getSpotPairsForCrypto(crypto: string): string[] {
    const normalizedCrypto = crypto.trim().toUpperCase();
    return this.getActiveSpotPairs().filter((pair) => pair.startsWith(`${normalizedCrypto}_`));
  }

  private normalizePair(pair: string): string | null {
    const normalized = pair?.trim().toUpperCase();
    if (!normalized || !normalized.includes('_')) return null;
    return normalized;
  }
}
