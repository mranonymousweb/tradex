import { IBotPayload } from '../interface/bot-payload.interface';
import { CoreService } from 'src/core/core.service';
import AsyncLock from 'async-lock';
import { CurrencyPriceService } from 'src/currency-price/currency-price.service';
import { EventService } from 'src/event/event.service';
import { BotStatusEnum } from '../enum/bot-status.enum';
import { spliter } from '../constant/spliter.constant';
import { Logger } from '@nestjs/common';
import { FuturesEngine, BotConfig } from './futures-engine';
import { BotTypeEnum } from '../enum/bot-type.enum';
import Decimal from 'decimal.js';

export class FutureBotHandler {
  private intervalId: NodeJS.Timeout | null = null;
  private readonly logger = new Logger(FutureBotHandler.name);

  private mainCurrencySymbol: string;
  private baseCurrencySymbol: string;

  private engine: FuturesEngine;

  constructor(
    private readonly marketPair: string,
    private readonly lock: AsyncLock,
    private readonly coreService: CoreService,
    private readonly currencyPriceService: CurrencyPriceService,
    private readonly eventService: EventService,
  ) {
    const parts = this.marketPair.split(spliter);

    this.mainCurrencySymbol = (parts[0] || '').toUpperCase().trim();
    this.baseCurrencySymbol = (parts[1] || 'USDT').toUpperCase().trim();

    this.engine = new FuturesEngine(new Decimal(0));
  }

  async init() {
    const payload = await this.coreService.getSetting(
      this.mainCurrencySymbol,
      this.baseCurrencySymbol,
      BotTypeEnum.FUTURE,
    );

    await this.handleBot(payload);
  }

  async destroy() {
    this.cancelJob();
     await this.coreService.emitBotFutureOrder(this.marketPair, {
        buyers: [],
        sellers: [],
        done: [],
      });
  }

  async handleBot(payload: IBotPayload) {
    console.log('handleBot', payload);

    this.cancelJob();

    if (!payload || payload.status === BotStatusEnum.INACTIVE) {
      await this.coreService.emitBotFutureOrder(this.marketPair, {
        buyers: [],
        sellers: [],
        done: [],
      });
      return;
    }

    const intervalTime = Number(payload.secount || 2) * 1000;

    const loop = async () => {
      if (!this.intervalId) return;

      try {
        const rawPrice = await this.currencyPriceService.getFuturePriceOf(
          this.mainCurrencySymbol,
          this.baseCurrencySymbol,
        );

        if (!rawPrice) {
          return;
        }

        const marketPrice = new Decimal(rawPrice);

        if (!marketPrice.isFinite() || marketPrice.lte(0)) {
          this.logger.warn(
            `Invalid futures market price for ${this.marketPair}: ${rawPrice}`,
          );
          return;
        }

        await this.lock.acquire(this.marketPair, async () => {
          await this.tick(payload, marketPrice);
        });
      } catch (e) {
        this.logger.error(`Futures cycle error ${this.marketPair}`, e);
      } finally {
        this.intervalId = setTimeout(loop, intervalTime) as any;
      }
    };

    this.intervalId = setTimeout(loop, 0) as any;
  }

  private async tick(payload: IBotPayload, marketPrice: Decimal) {
    const config: BotConfig = {
      buyPercent: new Decimal(payload.buyPercent || '0.001'),
      sellPercent: new Decimal(payload.sellPercent || '0.001'),

      /**
       * قیمت اولیه و قیمت مارکت هر دو از Redis / price service می‌آیند.
       */
      initialPrice: marketPrice,
      marketPrice,

      /**
       * این‌ها notional هستند، یعنی مقدار تتری سفارش.
       */
      leastAmount: new Decimal(payload.leastAmount || '100'),
      mostAmount: new Decimal(payload.mostAmount || '1000'),
    };

    /**
     * مهم:
     * هر tick باید marketPrice جدید وارد engine شود.
     */
    this.engine.configure(config);

    /**
     * تولید liquidity را به خود engine بسپار.
     * اینجا دیگر generateLiquidity صدا نزن.
     */
    this.engine.tick();

    const market = this.engine.getMarketView();

    await this.coreService.emitBotFutureOrder(this.marketPair, market);
  }

  private cancelJob() {
    if (this.intervalId) {
      clearTimeout(this.intervalId);
      this.intervalId = null;
    }
  }
}
