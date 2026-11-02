import { Injectable } from "@nestjs/common";
import Decimal from "decimal.js";
import { FutureIOrderPayload } from "src/common/interface/future-order-payload.interface";
import { FutureOrderService } from "src/future-order/future-order.service";
import { FutureExitType } from "src/future-order/entities/future-order.entity";

@Injectable()
export class FuturePositionCloseService {
  constructor(
    private readonly futureOrderService: FutureOrderService,
  ) {}

  async close(
    position: FutureIOrderPayload,
    marketPrice: Decimal,
    exitType: FutureExitType,
  ): Promise<{
      status: boolean;
      position: FutureIOrderPayload;
    }> {
    const price = new Decimal(marketPrice || 0);

    if (price.lte(0)) return {status: false, position: null};
    if (!position?.id) return {status: false, position: null};

     console.log("start closePositionTx", "id: ", position.id, "marketPrice: ", marketPrice, "exitType: ", exitType)
    return this.futureOrderService.closePositionTx({
      position: position,
      marketPrice: price,
      exitType,
    });
  }
}
