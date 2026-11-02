import { Injectable } from "@nestjs/common";
import { OrderSideEnum, OrderStatusEnum, OrderTypeEnum } from "src/common/enum/order.enum";
import { IOrderPayload } from "src/common/interface/order-payload.interface";
import { CoreService } from "src/core/core.service";
import { MarketCurrencyService } from "src/market-currency/market-currency.service";

@Injectable()
export class OrderValidationService {

  constructor(
    private readonly marketCurrencyService: MarketCurrencyService
  ) {}

  async validate(order: IOrderPayload): Promise<{ valid: boolean; reason?: string }> {

    // ===== 1. Structural Checks =====

    // check id field of order
    if (!order.id) return { valid: false, reason: "Missing order id" };

    // check pairs field of order
    if (!order.pairs) return { valid: false, reason: "Missing pair" };

    // check valid side of order
    if (![OrderSideEnum.BUY, OrderSideEnum.SELL].includes(order.side))
      return { valid: false, reason: "Invalid side" };

    // check valid type of order
    if (!Object.values(OrderTypeEnum).includes(order.type))
      return { valid: false, reason: "Invalid order type" };

    const amount = Number(order.amount);
    const price = Number(order.price);

    // check valid amount of order
    if (amount <= 0)
      return { valid: false, reason: "Invalid amount" };

    // check price by type
    // const validatePrice = await this.validatePrice(order.type, Number(order.price), Number(order.stop_price))
    // if (!validatePrice.valid)
    //   return { valid: false, reason: validatePrice?.reason};

    // check valid status for match 
    const allowedStatuses = [OrderStatusEnum.IN_PROGRESS, OrderStatusEnum.ACTIVE];
    if (!allowedStatuses.includes(order.status))
      return { valid: false, reason: "Invalid order status" };

    // ===== 2. Market Checks =====

    // check valid status for match of db
    const marketActive = await this.marketCurrencyService.checkActivePair(order.pairs);
    if (!marketActive)
      return { valid: false, reason: "Market not active" };



    return { valid: true };
  }

  // async validatePrice(
  //   type: OrderType,
  //   price?: number,
  //   stopPrice?: number
  // ) {
  //   switch (type) {
  //     // case OrderType.MARKET:
  //     //   if (price && price > 0)
  //     //     return { valid: false, reason: "Market order should not have price" };
  //     //   break;

  //     case OrderType.LIMIT:
  //     case OrderType.LIMIT_MAKER:
  //       if (!price || price <= 0)
  //         return { valid: false, reason: "Invalid limit price" };
  //       break;

  //     case OrderType.STOP_LIMIT:
  //       if (!price || price <= 0)
  //         return { valid: false, reason: "Invalid limit price" };
  //       if (!stopPrice || stopPrice <= 0)
  //         return { valid: false, reason: "Invalid stop price" };
  //       break;

  //     case OrderType.STOP_MARKET:
  //       if (!stopPrice || stopPrice <= 0)
  //         return { valid: false, reason: "Invalid stop price" };
  //       break;
  //   }

  //   return { valid: true };
  // }
}
