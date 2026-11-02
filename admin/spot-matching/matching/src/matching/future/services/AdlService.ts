import { Injectable } from '@nestjs/common';
import Decimal from 'decimal.js';
import { FutureIOrderPayload } from 'src/common/interface/future-order-payload.interface';
import { CurrencyService } from 'src/currency/currency.service';
import { FutureAdlSettingService } from 'src/future-adl-setting/future-adl-setting.service';
import { MarketCurrencyService } from 'src/market-currency/market-currency.service';

@Injectable()
export class AdlService {
  private targetRatio = new Decimal(60).div(40); // 1.5;

  // کش محلی به همراه زمان انقضا
  private readonly adlCache = new Map<string, { data: any; expiresAt: number }>();
  private readonly CACHE_TTL_MS = 60 * 1000; // ۱ دقیقه به میلی‌ثانیه

  constructor(
    private readonly marketCurrencyService: MarketCurrencyService,
    private readonly futureAdlSettingService: FutureAdlSettingService,
  ) {}

  // متد کمکی برای دریافت اطلاعات با مکانیزم کش ۱ دقیقه‌ای
  private async getFutureAdlSettingWithCache(pair: string, base_currency_id: number): Promise<any> {
    const now = Date.now();
    const cached = this.adlCache.get(pair);

    // اگر کش وجود دارد و منقضی نشده است، از کش استفاده کن
    if (cached && cached.expiresAt > now) {
      return cached.data;
    }

    // در غیر این صورت، از سرویس واکشی کن
    const data = await this.futureAdlSettingService.findByBaseCurrency(base_currency_id);
    
    // در کش ذخیره کن
    this.adlCache.set(pair, {
      data,
      expiresAt: now + this.CACHE_TTL_MS,
    });

    return data;
  }

  getPnlStats(positions: FutureIOrderPayload[]) {
    const totalPositivePnl = positions.reduce((sum, p) => {
      const pnl = new Decimal(p.pnl || 0);
      return pnl.gt(0) ? sum.plus(pnl) : sum;
    }, new Decimal(0));

    const totalNegativePnl = positions.reduce((sum, p) => {
      const pnl = new Decimal(p.pnl || 0);
      return pnl.lt(0) ? sum.plus(pnl.abs()) : sum;
    }, new Decimal(0));

    const ratio = totalPositivePnl.gt(0)
      ? totalNegativePnl.div(totalPositivePnl)
      : new Decimal(1);

    return {
      totalPositivePnl,
      totalNegativePnl,
      ratio,
    };
  }

  getHighestPriorityPosition(
    positions: FutureIOrderPayload[],
  ): FutureIOrderPayload | null {
    if (!positions.length) return null;

    return [...positions].sort((a, b) => {
      const pnlDiff = (b.pnl || 0) - (a.pnl || 0);
      if (pnlDiff !== 0) return pnlDiff;

      return (b.leverage || 0) - (a.leverage || 0);
    })[0];
  }

  /**
   * تا وقتی ratio به targetRatio نرسیده، سفارش‌های ADL را انتخاب می‌کند
   */
  async getAdlPositionsToClose(
    positions: FutureIOrderPayload[],
    pair: string,
    base_currency_id: number
  ): Promise<FutureIOrderPayload[]> {
    const remaining = [...positions];
    const selected: FutureIOrderPayload[] = [];
    
    // دریافت اطلاعات جفت‌ارز از کش ۱ دقیقه‌ای
    const futureAdlSetting = await this.getFutureAdlSettingWithCache(pair, base_currency_id);
    let  adlMinExchangeProfitPercent = 60;
    let minTradeAmountUsdt = 0;
    if(futureAdlSetting){
      adlMinExchangeProfitPercent = futureAdlSetting.adlMinExchangeProfitPercent
      minTradeAmountUsdt = futureAdlSetting.minTradeAmountUsdt
    }
    
    while (remaining.length > 0) {
      const getPnlStats = this.getPnlStats(remaining);
      const { ratio, totalPositivePnl } = getPnlStats;

      if(futureAdlSetting){
        this.targetRatio = new Decimal(adlMinExchangeProfitPercent).div(100 - adlMinExchangeProfitPercent)
      }
      console.log("adl setting", `ratio: ${ratio}`, `targetRatio: ${this.targetRatio}`, `totalPositivePnl: ${totalPositivePnl}`,`minTradeAmountUsdt: ${minTradeAmountUsdt }` )
      // اگر شرط 60/40 برقرار شد، توقف
      if ((ratio.gte(this.targetRatio)) || (minTradeAmountUsdt  > Number(totalPositivePnl) )) {
        break;
      }

      const candidate = this.getHighestPriorityPosition(remaining);
      if (!candidate) break;

      selected.push(candidate);

      // حذف از remaining
      const index = remaining.findIndex((p) => p.id === candidate.id);
      if (index >= 0) {
        remaining.splice(index, 1);
      }
    }

    return selected;
  }
}
