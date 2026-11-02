import { Injectable } from "@nestjs/common";
import { ModuleRef } from "@nestjs/core";
import { FutureMarketEngine } from "./future-market-engine";

@Injectable()
export class FutureEngineRegistry {
  // نگهداری engine ها به ازای هر جفت ارز
  private marketEngines = new Map<string, FutureMarketEngine>();
  private initializingMarkets = new Map<string, Promise<FutureMarketEngine>>();
  constructor(
    private readonly moduleRef: ModuleRef,

  ) {}

  // برگرداندن engine موجود
  getEngine(pair: string): FutureMarketEngine | undefined {
    return this.marketEngines.get(pair);
  }

  getAllEngines(): FutureMarketEngine[] {
    return Array.from(this.marketEngines.values());
  }

  async closeAllOrdersOnAllMarkets(): Promise<void> {
    for (const engine of this.getAllEngines()) {
      try {
        await engine.CloseAllOrders();
      } catch (error) {
        console.error('closeAllOrdersOnAllMarkets failed:', error);
      }
    }
  }

  // ایجاد یک MarketEngine جدید و ذخیره آن
 async initMarket(pair: string): Promise<FutureMarketEngine> {
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

private async createMarketEngine(pair: string): Promise<FutureMarketEngine> {
  const engine = await this.moduleRef.create(FutureMarketEngine);

  engine.configure(pair);
  await engine.init();

  // await this.pendingOrderLoaderService.load(pair, engine);

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
