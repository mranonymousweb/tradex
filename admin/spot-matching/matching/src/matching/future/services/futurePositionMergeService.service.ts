import { Injectable, Logger } from "@nestjs/common";
import Decimal from "decimal.js";
import { FutureIOrderPayload } from "src/common/interface/future-order-payload.interface";
import { FutureOrderService } from "src/future-order/future-order.service";

@Injectable()
export class FuturePositionMergeService {
  private readonly logger = new Logger(FuturePositionMergeService.name);

  constructor(
    private readonly futureOrderService: FutureOrderService,
  ) {}

  async merge(
    position: FutureIOrderPayload,
    order: FutureIOrderPayload,
    marketPrice: Decimal,
  ): Promise<FutureIOrderPayload | null> {
    
    // if (position.side !== order.side) {
    //   this.logger.error("Cannot merge opposite sides");
    //   return null;
    // }

    if (!marketPrice || marketPrice.lte(0)) {
      this.logger.error("Invalid market price");
      return null;
    }

    try {
      // فراخوانی متد تراکنشی از سرویس
      const updatedRow = await this.futureOrderService.mergeOrderIntoPosition(
        position.id,
        order.id,
        marketPrice
      );

      if (!updatedRow) {
        this.logger.error(`Merge failed in DB for pos:${position.id} and ord:${order.id}`);
        return null;
      }



      return {
        ...position,
        quantity: updatedRow.quantity,
        enter_price: updatedRow.enterPrice,
        cost: Number(updatedRow.cost),
        fee: Number(updatedRow.fee),
        side: updatedRow.side,
        leverage: Number(updatedRow.leverage),
        margin_mode: updatedRow.marginMode,
        // pnl و liq_price معمولاً توسط موتور قیمت در گام بعدی دوباره محاسبه می‌شوند
      };

    } catch (error) {
      this.logger.error(`Error during merge: ${error}`);
      return null;
    }
  }
}
