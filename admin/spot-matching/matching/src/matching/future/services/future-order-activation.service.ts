import { Injectable } from "@nestjs/common";
import { InjectRepository } from "@nestjs/typeorm";
import { Repository } from "typeorm";

import { FutureIOrderPayload } from "src/common/interface/future-order-payload.interface";
import { FutureOrder, FutureOrderStatus } from "src/future-order/entities/future-order.entity";
import Decimal from "decimal.js";
import { FutureOrderService } from "src/future-order/future-order.service";

@Injectable()
export class FutureOrderActivationService {
  constructor(
    private readonly futureOrderService: FutureOrderService,
  ) {}

 async activate(
  order: FutureIOrderPayload,
  marketPrice: Decimal,
): Promise<FutureIOrderPayload | null> {
  const now = new Date();

  try {

    const res = await this.futureOrderService.updatePendingToPos(order, now)

   
    if (!res.affected) {
      console.error("order not found in database for activate")
      return null;
    }

   
    return {
      ...order,
      status: FutureOrderStatus.CREATED,
      open_at: now,
    };
  } catch (err) {
    console.error(`Activation failed for order ${order.id}`, err);
    return null;
  }
}

}