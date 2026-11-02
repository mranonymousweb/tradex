import { Injectable } from "@nestjs/common";
import { ModuleRef } from "@nestjs/core";
import { MarketEngine } from "./market-engine";
import { PendingOrderLoaderService } from "./services/pending-orders.service";

@Injectable()
export class EngineRegistry {
  // نگهداری engine ها به ازای هر جفت ارز
  private marketEngines = new Map<string, MarketEngine>();
  private initializingMarkets = new Map<string, Promise<MarketEngine>>();
  constructor(
    private readonly moduleRef: ModuleRef,
    private readonly pendingOrderLoaderService: PendingOrderLoaderService

  ) {}

  // برگرداندن engine موجود
  getEngine(pair: string): MarketEngine | undefined {
    return this.marketEngines.get(pair);
  }

  // ایجاد یک MarketEngine جدید و ذخیره آن
 async initMarket(pair: string): Promise<MarketEngine> {
  // اگر قبلاً ساخته شده
  if (this.marketEngines.has(pair)) {
    return this.marketEngines.get(pair)!;
  }

  // اگر در حال ساخته شدن است، همان promise را برگردان
  if (this.initializingMarkets.has(pair)) {
    return this.initializingMarkets.get(pair)!;
  }

  // شروع ساخت engine
  const enginePromise = this.createMarketEngine(pair);

  // ذخیره promise برای جلوگیری از race
  this.initializingMarkets.set(pair, enginePromise);

  try {
    const engine = await enginePromise;

    this.marketEngines.set(pair, engine);
    this.initializingMarkets.delete(pair);

    return engine;
  } catch (error) {
    this.initializingMarkets.delete(pair);
    throw error;
  }
}

private async createMarketEngine(pair: string): Promise<MarketEngine> {
  const engine = await this.moduleRef.create(MarketEngine);

  engine.configure(pair);
  await engine.init();

  await this.pendingOrderLoaderService.load(pair, engine);

  return engine;
}

  // خاموش کردن و حذف یک engine
  async shutdownMarket(pair: string) {
    const engine = this.marketEngines.get(pair);
    if (!engine) return;

    await engine.destroy();
    this.marketEngines.delete(pair);
  }
}
