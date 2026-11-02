import { Injectable } from "@nestjs/common";
import Decimal from "decimal.js";
import { FutureIOrderPayload } from "src/common/interface/future-order-payload.interface";
import {
  FutureOrderSide,
  FutureExitType,
  FutureMarginMode,
} from "src/future-order/entities/future-order.entity";
import { UserAssetService } from "src/user-asset/user-asset.service";

export type ExitType = FutureExitType;

@Injectable()
export class FutureCloseValidationService {
  constructor(private readonly userAssetService: UserAssetService) {}

  async getExitType(
    position: FutureIOrderPayload,
    marketPrice: Decimal,
  ): Promise<ExitType | null> {

    const price = new Decimal(marketPrice || 0);

    if (price.lte(0)) {
      return null;
    }

    const side = position.side;

    const takeProfit = new Decimal(position.take_profit || 0);
    const stopLoss = new Decimal(position.stop_loss || 0);
    const liqPrice = new Decimal(position.liq_price || 0);
    console.log("getExitType", "id: ", position.id, "side: ", position.side,  "marketPrice: ", marketPrice, "enterPrice: ", position.enter_price, 
      "has_tpsl: ",position.has_tpsl ,"takeProfit : ", takeProfit, "stopLoss : ", stopLoss, "liqPrice : ", liqPrice, "pnl", position.pnl)



    /**
     * =========================
     * MARGIN / WALLET LIQUIDATION
     * Cross: |pnl منفی| > موجودی کیف پول → pnl = -موجودی کیف
     * Isolate: |pnl منفی| > enter_price → pnl = -enter_price
     * =========================
     */
    if (await this.shouldLiquidateByMargin(position)) {
      return FutureExitType.LIQUID;
    }

    /**
     * =========================
     * LIQUIDATION (highest priority)
     * =========================
     */
    if (liqPrice.gt(0)) {

      if (
        side === FutureOrderSide.LONG &&
        price.lte(liqPrice)
      ) {
        return FutureExitType.LIQUID;
      }

      if (
        side === FutureOrderSide.SHORT &&
        price.gte(liqPrice)
      ) {
        return FutureExitType.LIQUID;
      }
    }


    /**
     * =========================
     * TAKE PROFIT
     * =========================
     */
    if (position.has_tpsl && takeProfit.gt(0)) {

      if (
        side === FutureOrderSide.LONG &&
        price.gte(takeProfit)
      ) {
        return FutureExitType.TP;
      }

      if (
        side === FutureOrderSide.SHORT &&
        price.lte(takeProfit)
      ) {
        return FutureExitType.TP;
      }
    }

    /**
     * =========================
     * STOP LOSS
     * =========================
     */
    if (position.has_tpsl && stopLoss.gt(0)) {

      if (
        side === FutureOrderSide.LONG &&
        price.lte(stopLoss)
      ) {
        return FutureExitType.SL;
      }

      if (
        side === FutureOrderSide.SHORT &&
        price.gte(stopLoss)
      ) {
        return FutureExitType.SL;
      }
    }

    return null;
  }

  private async shouldLiquidateByMargin(
    position: FutureIOrderPayload,
  ): Promise<boolean> {
    const pnl = new Decimal(position.pnl || 0);

    // فقط وقتی pnl منفی است این شرط معنا دارد
    if (pnl.gte(0)) {
      return false;
    }

    const negativePnl = pnl.abs();

    if (position.margin_mode === FutureMarginMode.CROSS) {
      if (!position.user_id) {
        return false;
      }

      const symbol = position.base_currency_symbol || "USDT";
      const userAsset = await this.userAssetService.findbyUserIdAndSymbol(
        position.user_id,
        symbol,
      );
      const walletBalance = new Decimal(userAsset?.feature_balance ?? 0);

      if (negativePnl.gt(walletBalance)) {
        // سقف ضرر = موجودی کیف (مقدار منفی)
        position.pnl = walletBalance.neg().toNumber();
        return true;
      }

      return false;
    }

    if (position.margin_mode === FutureMarginMode.ISOLATE) {
      const enterPrice = new Decimal(position.enter_price || 0);

      if (negativePnl.gt(enterPrice)) {
        // سقف ضرر = enter_price (مقدار منفی)
        position.pnl = enterPrice.neg().toNumber();
        return true;
      }

      return false;
    }

    return false;
  }
}
