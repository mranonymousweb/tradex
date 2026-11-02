import { Injectable, Logger } from '@nestjs/common';
import { Cron } from '@nestjs/schedule';
import Decimal from 'decimal.js';
import { OptionsService } from 'src/options/options.service';
import { FutureOrderService } from 'src/future-order/future-order.service';
import { FutureHistoryService } from 'src/future-history/future-history.service';
import { FutureOrder } from 'src/future-order/entities/future-order.entity';

export interface PeriodicProfitRunResult {
  executed: boolean;
  periodKey?: string;
  periodTime?: string;
  profitRate?: string;
  processed: number;
  skipped: number;
  failed: number;
}

@Injectable()
export class FuturePeriodicProfitService {
  private readonly logger = new Logger(FuturePeriodicProfitService.name);
  private isRunning = false;
  /** جلوگیری از اجرای دوباره در همان دقیقه برای همان slot */
  private lastExecutedSlot: string | null = null;

  constructor(
    private readonly optionsService: OptionsService,
    private readonly futureOrderService: FutureOrderService,
    private readonly futureHistoryService: FutureHistoryService,
  ) {}

  /**
   * هر دقیقه چک می‌کند؛ اگر ساعت فعلی با یکی از سه period برابر بود، کارمزد اعمال می‌شود.
   */
  @Cron('* * * * *')
  async handleCron() {
    try {
      await this.runIfDue();
    } catch (error) {
      this.logger.error('FuturePeriodicProfit cron failed', error);
    }
  }

  async runIfDue(now: Date = new Date()): Promise<PeriodicProfitRunResult> {
    if (this.isRunning) {
      return { executed: false, processed: 0, skipped: 0, failed: 0 };
    }

    this.isRunning = true;

    try {
      const config = await this.optionsService.getFuturePeriodicProfitConfig();
      if (!config) {
        this.logger.warn('Periodic profit options not found');
        return { executed: false, processed: 0, skipped: 0, failed: 0 };
      }

      const currentTime = this.formatHhMm(now);
      console.log("config.periods", config.periods, "currentTime", currentTime)
      const matched = config.periods.find((p) => p.time === currentTime);

      if (!matched) {
        console.log("match time not found")
        return { executed: false, processed: 0, skipped: 0, failed: 0 };
      }
      console.log("match time found ", matched)

      const date = this.formatDate(now);
      const slotKey = `${date}:${matched.key}:${matched.time}`;

      if (this.lastExecutedSlot === slotKey) {
        return {
          executed: false,
          periodKey: matched.key,
          periodTime: matched.time,
          profitRate: config.profitRate,
          processed: 0,
          skipped: 0,
          failed: 0,
        };
      }

      const result = await this.applyPeriodicProfit({
        periodKey: matched.key,
        periodTime: matched.time,
        profitRate: config.profitRate,
        date,
      });

      this.lastExecutedSlot = slotKey;

      this.logger.log(
        `Periodic profit applied period=${matched.key} time=${matched.time} rate=${config.profitRate} processed=${result.processed} skipped=${result.skipped} failed=${result.failed}`,
      );

      return {
        executed: true,
        periodKey: matched.key,
        periodTime: matched.time,
        profitRate: config.profitRate,
        ...result,
      };
    } finally {
      this.isRunning = false;
    }
  }

  /**
   * اعمال کارمزد روی همه پوزیشن‌های CREATED:
   * feeToAdd = cost × future_periodic_profit
   * fee = fee + feeToAdd
   * + ثبت در feature_histories
   */
  async applyPeriodicProfit(params: {
    periodKey: string;
    periodTime: string;
    profitRate: string;
    date: string;
  }): Promise<{ processed: number; skipped: number; failed: number }> {
    // future_periodic_profit به درصد است → تقسیم بر ۱۰۰
    const rate = new Decimal(params.profitRate || 0).div(100);
    if (rate.lte(0)) {
      this.logger.warn(`Invalid profit rate: ${params.profitRate}`);
      return { processed: 0, skipped: 0, failed: 0 };
    }

    const orders = await this.futureOrderService.getAllCreatedOrders();

    let processed = 0;
    let skipped = 0;
    let failed = 0;

    for (const order of orders) {
      try {
        const applied = await this.applyOnOrder(order, rate, params);
        if (applied) {
          processed += 1;
        } else {
          skipped += 1;
        }
      } catch (error) {
        failed += 1;
        this.logger.error(
          `Periodic profit failed for order=${order.id}`,
          error,
        );
      }
    }

    return { processed, skipped, failed };
  }

  private async applyOnOrder(
    order: FutureOrder,
    rate: Decimal,
    params: {
      periodKey: string;
      periodTime: string;
      profitRate: string;
      date: string;
    },
  ): Promise<boolean> {
    const alreadyApplied =
      await this.futureHistoryService.hasPeriodicProfitForSlot(
        order.id,
        params.periodKey,
        params.date,
      );

    if (alreadyApplied) {
      return false;
    }

    const cost = new Decimal(order.cost || 0);
    if (cost.lte(0)) {
      return false;
    }

    const feeToAdd = cost.mul(rate);
    if (feeToAdd.lte(0)) {
      return false;
    }

    const updated = await this.futureOrderService.addPeriodicFee(
      order.id,
      feeToAdd,
    );

    if (!updated) {
      return false;
    }

    await this.futureHistoryService.createPeriodicProfitRecord({
      orderId: order.id,
      fee: feeToAdd.toFixed(12),
      periodKey: params.periodKey,
      periodTime: params.periodTime,
      profitRate: params.profitRate,
      date: params.date,
    });

    return true;
  }

  private formatHhMm(date: Date): string {
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${hours}:${minutes}`;
  }

  private formatDate(date: Date): string {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
  }
}
