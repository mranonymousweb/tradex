import { Injectable } from "@nestjs/common";
import Decimal from "decimal.js";
import {
  ASSET_TYPES,
  IOrderPayload,
} from "src/common/interface/order-payload.interface";
import { RedisService } from "src/redis/redis.service";

export type FeeRole = "taker" | "maker";

@Injectable()
export class FeeCalculationService {
  constructor(
    private readonly redisService: RedisService
  ) {}

  private toDecimal(value: any, fieldName: string): Decimal {
    if (value instanceof Decimal) return value;

    if (typeof value === "string" || typeof value === "number") {
      return new Decimal(value);
    }

    throw new Error(
      `Invalid decimal value for ${fieldName}: ${JSON.stringify(value)}`
    );
  }

  private async getPriceOrInverse(from: string, to: string): Promise<Decimal> {
    if (from === to) return new Decimal(1);

    const directPair = `${from}_${to}`;
    const inversePair = `${to}_${from}`;

    const direct = await this.redisService.getPairPrice(directPair);
    if (direct != null) {
      const directPrice = this.toDecimal(direct.last, `redis price ${directPair}`);
      if (directPrice.gt(0)) return directPrice;
    }

    const inverse = await this.redisService.getPairPrice(inversePair);
    if (inverse != null) {
      const inversePrice = this.toDecimal(inverse.last, `redis price ${inversePair}`);
      if (inversePrice.gt(0)) {
        return new Decimal(1).div(inversePrice);
      }
    }

    throw new Error(`Price not found for ${from}/${to}`);
  }

  async calcFee(
    item: IOrderPayload,
    amount: Decimal | string | number,
    type: FeeRole,
    isBuy: boolean,
    marketPrice: Decimal | string | number,
  ) {
    const tradeAmount = this.toDecimal(amount, "amount");
    const pairPrice = this.toDecimal(marketPrice, "marketPrice");

    const [mainCurrency, baseCurrency] = item.pairs.split("_");
    const assetType = item.meta?.asset_type;
    const isCurrency = !assetType || assetType === ASSET_TYPES.CURRENCY;

    let rate: Decimal;
    let feeMaxUsdt: Decimal;

    console.log("calcFee", item)
    if (isCurrency) {
      
      rate = this.toDecimal(
        type === "maker"
          ? item.meta.currency_maker_fee
          : item.meta.currency_taker_fee,
        `${type} currency fee rate`
      ).div(100);
      console.log("currency rate", rate)

      feeMaxUsdt = this.toDecimal(
        type === "maker"
          ? item.meta.currency_maker_fee_max
          : item.meta.currency_taker_fee_max,
        `${type} currency fee max`
      );
        console.log("currency feeMaxUsdt", feeMaxUsdt)
    } else {
      rate = this.toDecimal(
        type === "maker"
          ? item.meta.fiat_maker_fee
          : item.meta.fiat_taker_fee,
        `${type} fiat fee rate`
      ).div(100);
      console.log("fiat rate", rate)

      feeMaxUsdt = this.toDecimal(
        type === "maker"
          ? item.meta.fiat_maker_fee_max
          : item.meta.fiat_taker_fee_max,
        `${type} fiat fee max`
      );
      console.log("fiat feeMaxUsdt", feeMaxUsdt)
    }

    if (isBuy) {
        const rawFeeInMain = tradeAmount.mul(rate);

        const mainToUsdt = await this.getPriceOrInverse(
          mainCurrency,
          "USDT",
        );

        const maxFeeInMain = feeMaxUsdt.div(mainToUsdt);
        const finalFee = Decimal.min(rawFeeInMain, maxFeeInMain);
        return finalFee;

        return {
          amount: finalFee,
          currency: mainCurrency,
          rawAmount: rawFeeInMain,
          cappedAmount: maxFeeInMain,
          rate,
        };
      // const rawFeeInMain = tradeAmount.mul(rate);

      // const mainToUsdt = await this.getPriceOrInverse(mainCurrency, "USDT");
      // const maxFeeInMain = feeMaxUsdt.div(mainToUsdt);

      // console.log("isBuy", rawFeeInMain, maxFeeInMain)
      // return Decimal.min(rawFeeInMain, maxFeeInMain);
    }

    // const totalInBase = tradeAmount.mul(pairPrice);
    // const rawFeeInBase = totalInBase.mul(rate);

    // const usdtToBase = await this.getPriceOrInverse("USDT", baseCurrency);
    // const maxFeeInBase = feeMaxUsdt.mul(usdtToBase);

    // console.log("sell", rawFeeInBase, maxFeeInBase)
    // return Decimal.min(rawFeeInBase, maxFeeInBase);

    const totalInBase = tradeAmount.mul(pairPrice);
    const rawFeeInBase = totalInBase.mul(rate);

    const usdtToBase = await this.getPriceOrInverse(
      "USDT",
      baseCurrency,
    );

    const maxFeeInBase = feeMaxUsdt.mul(usdtToBase);
    const finalFee = Decimal.min(rawFeeInBase, maxFeeInBase);

    return finalFee
    return {
      amount: finalFee,
      currency: baseCurrency,
      rawAmount: rawFeeInBase,
      cappedAmount: maxFeeInBase,
      rate,
    };
  }
}
