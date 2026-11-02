import { BeforeApplicationShutdown, Inject, Injectable, OnModuleDestroy, OnModuleInit } from "@nestjs/common";
import { SupplierExchangeService } from "src/supplier-exchange/supplier-exchange.service";
import { Consumer, Kafka } from "kafkajs";
import { KAFKA_CLIENT } from "src/kafka/constant/kafka-client.constant";
import { ProviderFutureFactory } from "./provider.future.factory";
import { ProviderBase } from "./base.provider";

@Injectable()
export class ExchangeFutureManager implements OnModuleInit, OnModuleDestroy, BeforeApplicationShutdown {
  constructor(
    private readonly factory: ProviderFutureFactory,
    private readonly supplierExchangeService: SupplierExchangeService,
    @Inject(KAFKA_CLIENT) private readonly kafka: Kafka,
  ) {}

  private consumer: Consumer;
  private exchanges: ProviderBase[] = [];
  private isSwitching = false;

  // ========== مقداردهی اولیه ==========
  async onModuleInit() {
    await this.startExchangesFromSupplier();
    await this.initKafkaConsumer();
  }

  // ========== توقف همه اکسچنج‌ها ==========
  private async stopAllExchanges() {
    if (this.exchanges.length === 0) return;
    await Promise.all(this.exchanges.map(e => e.disconnect()));
    this.exchanges = [];
  }

  // ========== راه‌اندازی اکسچنج‌ها ==========
  private async startExchanges(names: string[]) {
    if (!names.length) return;

    // names = ["binance", "bybit"]
     names = [ "gate_io"]
    for (const name of names) {
      const providers = this.factory.get(name); // حالا یک آرایه برمی‌گرداند
      for (const provider of providers) {
        this.exchanges.push(provider);
        await provider.connect();
      }
    }
  }

  // ========== خواندن supplier از دیتابیس و راه‌اندازی ==========
  private async startExchangesFromSupplier() {
    const supplier = await this.supplierExchangeService.getActiveSupplier();

    const supplierMap: Record<string, string[]> = {
      binance: ['binance'],
      bybit: ["bybit"],
      gateio: ["gateio"]
    };

    const names = supplier?.name && supplierMap[supplier.name]
      ? supplierMap[supplier.name]
      : ['gateio'];

    console.log("active providers are future:", names);

    await this.startExchanges(names);
  }

  // ========== سوییچ به provider جدید ==========
  private async switchProvider(newProviderName: string) {
    if (this.isSwitching) {
      console.warn('[ExchangeManager] Switch already in progress, ignoring...');
      return;
    }
    this.isSwitching = true;

    try {
      await this.stopAllExchanges();

      const supplierMap: Record<string, string[]> = {
        binance: ['binance'],
      };
      const names = supplierMap[newProviderName] || ['nobitex'];

      await this.startExchanges(names);

      console.log(`[ExchangeManager] Successfully switched to ${newProviderName}`);
    } catch (error) {
      console.error(`[ExchangeManager] Failed to switch to ${newProviderName}:`, error);
    } finally {
      this.isSwitching = false;
    }
  }

  // ========== کانفیگ مصرف‌کننده کافکا ==========
  private async initKafkaConsumer() {
    try {
      this.consumer = this.kafka.consumer({ groupId: 'currency-price-provider-group' });
      await this.consumer.connect();
      await this.consumer.subscribe({ topic: 'currency-price-provider' });

      await this.consumer.run({
        autoCommit: false,
        eachBatch: async ({ batch, resolveOffset, heartbeat, commitOffsetsIfNecessary, isRunning, isStale }) => {
          for (const message of batch.messages) {
            if (!isRunning() || isStale()) break;
            if (!message.key || !message.value) continue;

            const providerName = message.key.toString();
            console.log(`[ExchangeManager] Received provider change request: ${providerName}`);

            await this.switchProvider(providerName);

            resolveOffset(message.offset);
            await heartbeat();
          }
          await commitOffsetsIfNecessary();
        },
      });
    } catch (error) {
      console.error('[ExchangeManager] Kafka consumer error:', error);
    }
  }

  // ========== مدیریت خاموشی ==========
  async onModuleDestroy() {
    console.log('[ExchangeManager] module destroy');
    await this.stopAllExchanges();
    if (this.consumer) {
      await this.consumer.disconnect();
    }
  }

  async beforeApplicationShutdown(signal: string) {
    console.log('[ExchangeManager] app shutdown', signal);
    await this.stopAllExchanges();
    if (this.consumer) {
      await this.consumer.disconnect();
    }
  }
}