import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource, QueryRunner } from "typeorm";
import { BaseRepository } from "./base.repository";
import { MarketEntity } from "../entity/market.entity";
import Decimal from "decimal.js";
import { OrderStatusEnum } from "src/common/enum/order.enum";
import { MarketHistoryEntity } from "../entity/market-history.entity";
import { UserAssetEntity } from "../entity/user-asset.entity";
import { UserTransactionEntity } from "../entity/user-transaction.entity";

@Injectable()
export class MarketRepository extends BaseRepository<MarketEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(MarketEntity, dataSource);
  }

  async executeTrade(buyOrder: MarketEntity, sellOrder: MarketEntity): Promise<void> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();
    await queryRunner.startTransaction();

    try {
      const isBuyTaker = buyOrder.createdAt > sellOrder.createdAt;
      const tradePrice = isBuyTaker ? sellOrder.price : buyOrder.price;
      
      const tradeAmount = Decimal.min(buyOrder.filledAmount, sellOrder.filledAmount); 

      const updateOrderStatus = (order: MarketEntity) => {
          const isDone = new Decimal(order.filledReceiveAmount).lte(0);
          return {
              status: isDone ? OrderStatusEnum.DONE : OrderStatusEnum.ACTIVE,
              doneAt: isDone ? new Date() : null
          };
      };

      const buyStatus = updateOrderStatus(buyOrder);
      const sellStatus = updateOrderStatus(sellOrder);

      const getFee = (order: MarketEntity, opositeOrder: MarketEntity, currentTradeAmount: Decimal, currentTradePrice: Decimal) => {
        let feePercent: string;
        let feeMaxPercent: string;

        const isOrderTaker = order.createdAt > opositeOrder.createdAt;

        if(order?.mainCurrency?.type === 1) {
          feePercent = isOrderTaker ? order?.user?.package?.currencyTakerFee : order?.user?.package?.currencyMakerFee;
          feeMaxPercent = isOrderTaker ? order?.user?.package?.currencyTakerFeeMax : order?.user?.package?.currencyMakerFeeMax;
        } else {
          feePercent =  isOrderTaker ? order?.user?.package?.fiatTakerFee : order?.user?.package?.fiatMakerFee;
          feeMaxPercent = isOrderTaker ? order?.user?.package?.fiatTakerFeeMax : order?.user?.package?.fiatMakerFeeMax;
        }

        if (!feePercent) feePercent = "0";
        if (!feeMaxPercent) feeMaxPercent = "0";

        const currentTradeValue = currentTradeAmount.mul(currentTradePrice);

        let calculatedFee = currentTradeValue.mul(new Decimal(feePercent).div(100));
        const maxFee = currentTradeValue.mul(new Decimal(feeMaxPercent).div(100));

        if (calculatedFee.gt(maxFee) && !maxFee.isZero()) {
          calculatedFee = maxFee;
        }

        return {
          fee: calculatedFee,
          currntTotal: currentTradeValue
        };
      };

      const buyFactor = getFee(buyOrder, sellOrder, tradeAmount, new Decimal(tradePrice));
      const sellFactor = getFee(sellOrder, buyOrder, tradeAmount, new Decimal(tradePrice));

      await queryRunner.manager.update(MarketEntity, buyOrder.id, { ...buyStatus, filledAmount: buyOrder.filledAmount, filledReceiveAmount: buyOrder.filledReceiveAmount, fee: buyFactor.fee.toString(), marketPrice: tradePrice });
      await queryRunner.manager.update(MarketEntity, sellOrder.id, { ...sellStatus, filledAmount: sellOrder.filledAmount, filledReceiveAmount: sellOrder.filledReceiveAmount, fee: sellFactor.fee.toString(), marketPrice: tradePrice });

      await queryRunner.manager.insert(MarketHistoryEntity, {
        marketId: buyOrder.id,
        subMarketId: sellOrder.id,
        amount: tradeAmount.toString(),
        price: tradePrice,
        globalPrice: '0'
      });

      const buyerReceiveNet = buyFactor.currntTotal.sub(buyFactor.fee);
      const sellerReceiveNet = sellFactor.currntTotal.sub(sellFactor.fee);

      const buyerAssetId = await this.incrementBalanceAndCreateAsset(
        queryRunner,
        buyOrder.userId,
        buyOrder.mainCurrencyId,
        buyOrder.mainCurrencySymbol,
        buyerReceiveNet,
      );

      await this.logTransaction(
        queryRunner,
        buyOrder.userId,
        buyOrder.mainCurrencyId,
        buyerAssetId,
        buyerReceiveNet,
        buyFactor.fee,
        `واریز بابت معاملات اسپات"`,
        2,
        1,
        'App\\Models\\Market',
        buyOrder.id.toString()
      );

      const sellerAssetId = await this.incrementBalanceAndCreateAsset(
        queryRunner,
        sellOrder.userId,
        sellOrder.baseCurrencyId,
        sellOrder.baseCurrencySymbol,
        sellerReceiveNet,
      );

      await this.logTransaction(
        queryRunner,
        sellOrder.userId,
        sellOrder.baseCurrencyId,
        sellerAssetId,
        sellerReceiveNet,
        sellFactor.fee,
        `واریز بابت معاملات اسپات`,
        2,
        1,
        'App\\Models\\Market',
        sellOrder.id.toString()
      );
      
      await queryRunner.commitTransaction();
    } catch (error) {
      await queryRunner.rollbackTransaction();
      throw error;
    } finally {
      await queryRunner.release();
    }
  }

  private async incrementBalanceAndCreateAsset(
    queryRunner: QueryRunner,
    userId: number,
    currencyId: number,
    symbol: string,
    amount: Decimal,
  ): Promise<number> {
    let asset = await queryRunner.manager.findOne(UserAssetEntity, {
      where: { userId, currencyId },
    });

    if (!asset) {
      asset = queryRunner.manager.create(UserAssetEntity, {
        userId,
        currencyId,
        symbol: symbol,
        balance: '0',
        blockBalance: '0',
        featureBalance: '0',
        marginBalance: '0',
        statingBalance: '0',
      });
      await queryRunner.manager.save(UserAssetEntity, asset);
    }

    await queryRunner.manager.increment(
      UserAssetEntity,
      { id: asset.id },
      'balance',
      amount.toString(),
    );

    return asset.id;
  }

  private async logTransaction(
    queryRunner: QueryRunner,
    userId: number,
    currencyId: number,
    assetId: number,
    finalAmount: Decimal,
    fee: Decimal,
    description: string,
    section: number,
    type: number,
    transactionableType: string,
    transactionableId: string
  ) {
    await queryRunner.manager.insert(UserTransactionEntity, {
      userId,
      currencyId,
      assetId,
      amount: finalAmount.plus(fee).toString(),
      finalAmount: finalAmount.toString(),
      fee: fee.toString(),
      description,
      section,
      type,
      status: 2,
      transactionableType,
      transactionableId,
      createdAt: new Date(),
    });
  }
}