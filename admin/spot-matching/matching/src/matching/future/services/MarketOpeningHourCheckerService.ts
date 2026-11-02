import { Injectable, Logger } from '@nestjs/common';
import { Cron } from '@nestjs/schedule';
import { MarketOpeningHourService } from 'src/market-opening-hour/market-opening-hour.service';
import { MarketOpeningHourDay } from 'src/common/enum/market-opening-hour.enum';
import { FutureEngineRegistry } from '../future-engine-registry';

const WEEK_DAYS: MarketOpeningHourDay[] = [
  MarketOpeningHourDay.SUNDAY,
  MarketOpeningHourDay.MONDAY,
  MarketOpeningHourDay.TUESDAY,
  MarketOpeningHourDay.WEDNESDAY,
  MarketOpeningHourDay.THURSDAY,
  MarketOpeningHourDay.FRIDAY,
  MarketOpeningHourDay.SATURDAY,
];

@Injectable()
export class MarketOpeningHourCheckerService {
  private readonly logger = new Logger(MarketOpeningHourCheckerService.name);

  constructor(
    private readonly marketOpeningHourService: MarketOpeningHourService,
    private readonly futureEngineRegistry: FutureEngineRegistry,
  ) {}

  /** هر ۵ دقیقه تنظیمات روز جاری را چک می‌کند */
  @Cron('*/1 * * * *')
  async handleCron() {
    try {
      await this.checkCurrentDayWindow();
    } catch (error) {
      this.logger.error('MarketOpeningHourChecker cron failed', error);
    }
  }

  async checkCurrentDayWindow(now: Date = new Date()): Promise<boolean> {
    const day = WEEK_DAYS[now.getDay()];
    const setting = await this.marketOpeningHourService.findByDay(day, [
      'day',
      'openTime',
      'closeTime',
      'isActive',
    ]);

    if (!setting) {
      this.logger.warn(`No market opening hour setting found for day=${day}`);
      return true;
    }

    const currentMinutes = this.toMinutes(this.formatHhMmSs(now));
    const openMinutes = this.parseTimeToMinutes(setting.openTime);
    const closeMinutes = this.parseTimeToMinutes(setting.closeTime);

    // هیچ‌کدام مقدار ندارند → شرطی اعمال نمی‌شود
    if (openMinutes === null && closeMinutes === null) {
      return true;
    }

    const isOutside = this.isOutsideWindow(
      currentMinutes,
      openMinutes,
      closeMinutes,
    );

    if (isOutside) {
      console.log(
        `[MarketOpeningHour] outside window day=${day} now=${this.formatHhMmSs(now)} open=${setting.openTime ?? null} close=${setting.closeTime ?? null}`,
      );
      await this.futureEngineRegistry.closeAllOrdersOnAllMarkets();
    }

    return !isOutside;
  }

  /**
   * - هر دو مقدار: خارج از بازه اگر قبل از open یا بعد از close باشد
   * - فقط open: خارج اگر قبل از open باشد
   * - فقط close: خارج اگر بعد از close باشد
   */
  private isOutsideWindow(
    currentMinutes: number,
    openMinutes: number | null,
    closeMinutes: number | null,
  ): boolean {
    if (openMinutes !== null && closeMinutes !== null) {
      return currentMinutes < openMinutes || currentMinutes > closeMinutes;
    }

    if (openMinutes !== null) {
      return currentMinutes < openMinutes;
    }

    if (closeMinutes !== null) {
      return currentMinutes > closeMinutes;
    }

    return false;
  }

  private parseTimeToMinutes(value?: string | null): number | null {
    if (value == null || String(value).trim() === '') {
      return null;
    }

    return this.toMinutes(String(value));
  }

  /** "HH:MM:SS" یا "HH:MM" → دقیقه از نیمه‌شب */
  private toMinutes(time: string): number {
    const [hh = '0', mm = '0'] = time.split(':');
    return Number(hh) * 60 + Number(mm);
  }

  private formatHhMmSs(date: Date): string {
    const hh = String(date.getHours()).padStart(2, '0');
    const mm = String(date.getMinutes()).padStart(2, '0');
    const ss = String(date.getSeconds()).padStart(2, '0');
    return `${hh}:${mm}:${ss}`;
  }
}
