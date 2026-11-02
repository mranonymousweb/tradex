import { Injectable } from '@nestjs/common';
import { IOrderPayload } from 'src/common/interface/order-payload.interface';
import { CoreService } from 'src/core/core.service';
import { MarketEngine } from '../market-engine';
import { OrderValidationService } from './order-validation.service';
import { OrderActivationService } from './order-activation.service';
import { Market } from 'src/market/entities/market.entity';
import { MarketService } from 'src/market/market.service';

@Injectable()
export class PendingOrderLoaderService {

  constructor(
    private readonly marketService: MarketService,
    private readonly orderValidationService: OrderValidationService,
    private readonly orderActivationService: OrderActivationService,
  ) {}

  /**
   * بارگذاری سفارش‌های مانده از DB و قرار دادن آن‌ها در queue یا waitingOrders
   * @param engine MarketEngine مربوط به یک pair
   */
   async load(pair: string, engine: MarketEngine) {
    console.log("get pending orders", pair)
    const marketEntities: Market[] = await this.marketService.getMarketPendingOrders(pair);
    const pendingOrders: IOrderPayload[] = marketEntities.map(entity => ({
        id: entity.id,
        user_id: entity.userId,
        main_currency_symbol: entity.mainCurrencySymbol,
        base_currency_symbol: entity.baseCurrencySymbol,
        pairs: entity.pairs,
        side: entity.side,
        type: entity.type,
        leverage: entity.leverage,
        amount: entity.amount,
        price: entity.price,
        avg_price: entity.avgPrice ?? '0',
        total: entity.total ?? '0',
        stop_price: entity.stopPrice ?? '0',
        market_price: entity.marketPrice ?? '0',
        filled_receive_amount: entity.filledReceiveAmount ?? '0',
        filled_amount: entity.filledAmount ?? '0',
        filled_total: entity.filledTotal ?? '0',
        fee: entity.fee ?? '0',
        fee_percent: entity.feePercent ?? '0',
        status: entity.status,
        open_at: entity.openAt ? entity.openAt.toISOString() : null,
        done_at: entity.doneAt ? entity.doneAt.toISOString() : null,
        canceled_at: entity.canceledAt ? entity.canceledAt.toISOString() : null,
        created_at: entity.createdAt.toISOString(),
        updated_at: entity.updatedAt.toISOString(),
        deleted_at: entity.deletedAt ? entity.deletedAt.toISOString() : null,
        meta: entity.meta ?? ""
    }));

    for (const order of pendingOrders) {
      // 1️⃣ اعتبارسنجی سفارش
      // const validation = await this.orderValidationService.validate(order);
      // if (!validation.valid) continue;

      // 2️⃣ بررسی activation
      // const isActive = await this.orderActivationService.handleOrderActivation(order);
      // const isActive = await this.orderActivationService.handleNewOrder(order, engine.getLastTradePrice());

     

      // if (isActive) {
        // سفارش فعال → به queue برای matching اضافه می‌شود
        await engine.processOrder(order);
      // }
      // سفارش غیر فعال → handleOrderActivation خودش آن را به waitingOrders اضافه می‌کند
    }
  }
}
