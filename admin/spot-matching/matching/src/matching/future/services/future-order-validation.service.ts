import { Injectable } from "@nestjs/common";
import Decimal from "decimal.js";
import { FutureIOrderPayload } from "src/common/interface/future-order-payload.interface";
import { FutureOrderSide, FutureOrderType } from "src/future-order/entities/future-order.entity";

export interface ValidationResult {
  valid: boolean;
  reason?: string;
}

@Injectable()
export class FutureOrderValidationService {
  async validateForActivation(
    order: FutureIOrderPayload,
    marketPrice: Decimal,
  ): Promise<ValidationResult> {
    if (!marketPrice || marketPrice.lte(0)) {
      console.error("Invalid market price in validateForActivation ", order.id)
      return {
        valid: false,
        reason: "Invalid market price",
      };
    }

    /**
     * برای سفارش pending، قیمت فعال‌سازی معمولاً price است،
     * نه enter_price.
     */
    if (!order.price) {
       console.error("Invalid order price in validateForActivation ", order.id)
      return {
        valid: false,
        reason: "Invalid order price",
      };
    }

    let entryPrice: Decimal;

    try {
      entryPrice = new Decimal(order.enter_price);
    } catch {
       console.error(`Invalid order enter_price: ${order.enter_price} in validateForActivation ` )
      return {
        valid: false,
        reason: `Invalid order enter_price: ${order.enter_price}`,
      };
    }

    if (entryPrice.lte(0)) {
      console.error(`Invalid order enter_price in validateForActivation `, order.id )
      return {
        valid: false,
        reason: "Invalid order enter_price",
      };
    }

    // manage market 
    if (((order.side === FutureOrderSide.LONG) || (order.side === FutureOrderSide.SHORT)) && (order.type == FutureOrderType.MARKET)) {
      return {
        valid: true,
        reason: "validate done",
      };
    }
    /**
     * LONG LIMIT
     *
     * Entry/Limit: 100
     * Current: 95
     *
     * فعال شود
     */
    if (order.side === FutureOrderSide.LONG) {
      const shouldActivate = marketPrice.lte(entryPrice);

      return {
        valid: shouldActivate,
        reason: shouldActivate
          ? undefined
          : `Waiting LONG activation. Price=${entryPrice.toString()} Market=${marketPrice.toString()}`,
      };
    }

    /**
     * SHORT LIMIT
     *
     * Entry/Limit: 100
     * Current: 105
     *
     * فعال شود
     */
    if (order.side === FutureOrderSide.SHORT) {
      const shouldActivate = marketPrice.gte(entryPrice);

      return {
        valid: shouldActivate,
        reason: shouldActivate
          ? undefined
          : `Waiting SHORT activation. Price=${entryPrice.toString()} Market=${marketPrice.toString()}`,
      };
    }

    console.error(`Unknown side: ${order.side}  in validateForActivation`, order.id)
    return {
      valid: false,
      reason: `Unknown side: ${order.side}`,
    };
  }
}
