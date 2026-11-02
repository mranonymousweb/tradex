import { BeforeApplicationShutdown, Inject, Injectable, OnModuleDestroy, OnModuleInit } from "@nestjs/common";
import { SupplierExchangeService } from "src/supplier-exchange/supplier-exchange.service";
import { Consumer, Kafka } from "kafkajs";
import { KAFKA_CLIENT } from "src/kafka/constant/kafka-client.constant";
import { ProviderFactory } from "./provider.factory";
import { ProviderBase } from "./base.provider";
import { SPOT_PROVIDER_NAME } from "./provider.interface";

@Injectable()
export class ExchangeManager implements OnModuleInit, OnModuleDestroy, BeforeApplicationShutdown {
  constructor(
    private readonly factory: ProviderFactory,
    private readonly supplierExchangeService: SupplierExchangeService,
    @Inject(KAFKA_CLIENT) private readonly kafka: Kafka,
  ) {}

  private consumer: Consumer;
  private exchanges: ProviderBase[] = [];
  private isSwitching = false; // قفل برای جلوگیری از تداخل

  // private resolveProviderName(name?: string): string {
  //   if (!name || name === SPOT_PROVIDER_NAME) {
  //     return SPOT_PROVIDER_NAME;
  //   }

  //   console.warn(
  //     `[ExchangeManager] Unsupported provider "${name}", falling back to ${SPOT_PROVIDER_NAME}`,
  //   );
  //   return SPOT_PROVIDER_NAME;
  // }

  // ========== مقداردهی اولیه در استارت ==========
  async onModuleInit() {
    // 1. راه‌اندازی اولیه بر اساس supplier دیتابیس
    await this.startExchangesFromSupplier();

    // 2. راه‌اندازی کانفیگ کافکا برای گوش دادن به تغییرات
    await this.initKafkaConsumer();
  }

  // ========== توقف همه اکسچنج‌ها ==========
  private async stopAllExchanges() {
    if (!this.exchanges) return;
    if (this.exchanges.length === 0) return;
    await Promise.all(this.exchanges.map(e => e.disconnect()));
    this.exchanges = [];
  }

  // ========== راه‌اندازی اکسچنج‌ها بر اساس لیست نام ==========
  private async startExchanges(names: string[]) {
    if (!names.length) {
      return;
    }
    for (const name of names) {
      const exchange = this.factory.get(name);
      this.exchanges.push(exchange);
      await exchange.connect();
    }
  }

  // ========== خواندن supplier از دیتابیس و راه‌اندازی ==========
  // private async startExchangesFromSupplier() {
  //   const supplier = await this.supplierExchangeService.getActiveSupplier();
  //   const providerName = this.resolveProviderName(supplier?.name);

  //   console.log('active provider is : ', [providerName]);

  //   await this.startExchanges([providerName]);
  // }


  // ========== خواندن supplier از دیتابیس و راه‌اندازی ==========
  private async startExchangesFromSupplier() {
    const supplier = await this.supplierExchangeService.getActiveSupplier();

    const supplierMap: Record<string, string[]> = {
      coinex: ['coinex'],
      nobitex: ["nobitex"],
      gate_io: ["gate_io"]
    };

    const names = supplier?.name && supplierMap[supplier.name]
      ? supplierMap[supplier.name]
      : ["gate_io"];

    console.log("active providers spot are:", names, supplier);

    await this.startExchanges(names);
  }

  // // ========== سوییچ به provider جدید بر اساس نام ==========
  // private async switchProvider(newProviderName: string) {
  //   if (this.isSwitching) {
  //     console.warn('[ExchangeManager] Switch already in progress, ignoring...');
  //     return;
  //   }
  //   this.isSwitching = true;

  //   try {

  //     // توقف همه اتصالات فعلی
  //     await this.stopAllExchanges();

  //     const providerName = this.resolveProviderName(newProviderName);

  //     await this.startExchanges([providerName]);

  //     console.log(`[ExchangeManager] Successfully switched to ${providerName}`);
  //   } catch (error) {
  //     console.error(`[ExchangeManager] Failed to switch to ${newProviderName}:`, error);
  //   } finally {
  //     this.isSwitching = false;
  //   }
  // }

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
        nobtex: ['nobtex'],
        coinex: ['coinex'],
        gate_io: ['gate_io'],
      };
      const names = supplierMap[newProviderName];

      await this.startExchanges(names);

      console.log(`[ExchangeManager] Successfully switched to ${newProviderName}`);
    } catch (error) {
      console.error(`[ExchangeManager] Failed to switch to ${newProviderName}:`, error);
    } finally {
      this.isSwitching = false;
    }
  }

  // ========== کانفیگ مصرف‌کننده کافکا (فقط یک بار) ==========
  private async initKafkaConsumer() {
    try {
        this.consumer = this.kafka.consumer({ groupId: 'currency-price-provider-group-future' });
        await this.consumer.connect();
        await this.consumer.subscribe({ topic: 'currency-price-provider' });
        console.log("start kafka for change provider" )
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
            console.log("start kafka for change provider 11111111111")
            for (const message of batch.messages) {
            if (!isRunning() || isStale()) break;
            if (!message.key || !message.value) continue;

            const providerName = message.key.toString();
            console.log(`[ExchangeManager] Received provider change request: ${providerName}`);

            // انجام سوییچ (بدون فراخوانی دوباره onModuleInit/onModuleDestroy)
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

  // ========== حذف getProvider اضافی (دیگر نیاز نیست) ==========
  // حذف متد getProvider قبلی

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