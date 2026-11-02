import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { FutureOrder, FutureOrderSide, FutureOrderStatus, FutureExitType } from './entities/future-order.entity';
import { Repository, In, EntityManager } from 'typeorm';
import Decimal from 'decimal.js';
import { FutureIOrderPayload } from 'src/common/interface/future-order-payload.interface';
import { UserAssetService } from 'src/user-asset/user-asset.service';
import { UserTransactionService } from 'src/user-transaction/user-transaction.service';
import { MarketCurrencyService } from 'src/market-currency/market-currency.service';
import { FutureAdlSettingService } from 'src/future-adl-setting/future-adl-setting.service';

@Injectable()
export class FutureOrderService {
  constructor(
    @InjectRepository(FutureOrder)
    private readonly futureOrderRepository: Repository<FutureOrder>,
    private readonly userAssetService: UserAssetService,
    private readonly userTransactionService: UserTransactionService,
    // private readonly marketCurrencyService: MarketCurrencyService,
    private readonly futureAdlSettingService: FutureAdlSettingService,
  ) {}

  async getPendingOrders(pair: string): Promise<FutureOrder[]> {
    return this.futureOrderRepository.find({
      where: {
        pairs: pair,
        status: FutureOrderStatus.PENDING,
      },
      order: {
        id: 'ASC',
      },
    });
  }

  async getOpenOrders(pair: string): Promise<FutureOrder[]> {
    return this.futureOrderRepository.find({
      where: {
        pairs: pair,
        status: FutureOrderStatus.CREATED,
      },
      order: {
        id: 'ASC',
      },
    });
  }

  async getAllCreatedOrders(): Promise<FutureOrder[]> {
    return this.futureOrderRepository.find({
      where: {
        status: FutureOrderStatus.CREATED,
      },
      order: {
        id: 'ASC',
      },
    });
  }

  /**
   * کارمزد دوره‌ای را به fee سفارش اضافه می‌کند
   * feeToAdd = cost × future_periodic_profit
   */
  async addPeriodicFee(
    orderId: number,
    feeToAdd: Decimal,
  ): Promise<FutureOrder | null> {
    if (feeToAdd.lte(0)) {
      return null;
    }

    return this.futureOrderRepository.manager.transaction(async (manager) => {
      const order = await manager.findOne(FutureOrder, {
        where: { id: orderId },
        lock: { mode: 'pessimistic_write' },
      });

      if (!order || order.status !== FutureOrderStatus.CREATED) {
        return null;
      }

      const currentFee = new Decimal(order.fee || 0);
      order.fee = currentFee.plus(feeToAdd).toFixed(12);

      return manager.save(order);
    });
  }

  async getUnicMarkets(): Promise<string[]> {
    const rows = await this.futureOrderRepository
      .createQueryBuilder('o')
      .select('DISTINCT o.pairs', 'pairs')
      .where('o.status IN (:...statuses)', {
        statuses: [
          FutureOrderStatus.PENDING,
          FutureOrderStatus.CREATED,
        ],
      })
      .getRawMany();

    return rows.map(r => r.pairs);
  }

   async updatePendingToPos(order, now){
    return  await this.futureOrderRepository.update(order.id, {
      status: FutureOrderStatus.CREATED,
      openedAt: now,
    });
  }

  async findById(id){
    return await this.futureOrderRepository.findOne({
        where: {
          id: id,
        },
        lock: {
          mode: "pessimistic_write",
        },
      });
  }

  async save(order: FutureOrder): Promise<FutureOrder> {
    return this.futureOrderRepository.save(order);
  }
   // متد جدید برای عملیات Merge در سطح دیتابیس
  async mergeOrderIntoPosition(
    positionId: number,
    orderId: number,
    marketPrice: Decimal,
  ): Promise<FutureOrder | null> {
    return await this.futureOrderRepository.manager.transaction(async (manager: EntityManager) => {
      // 1. لاک کردن پوزیشن فعلی
      const positionRow = await manager.findOne(FutureOrder, {
        where: { id: positionId },
        lock: { mode: "pessimistic_write" },
      });

      if (!positionRow || positionRow.status !== FutureOrderStatus.CREATED) {
        console.error("position not found or status in not valid in mergeOrderIntoPosition")
        return null;
      }

      // 2. لاک کردن سفارش جدید
      const orderRow = await manager.findOne(FutureOrder, {
        where: { id: orderId },
        lock: { mode: "pessimistic_write" },
      });

      if (!orderRow || orderRow.status !== FutureOrderStatus.PENDING) {
        console.error("order not found or status in not valid in mergeOrderIntoPosition")
        return null;
      }

      // 3. محاسبات
      const posQty = new Decimal(positionRow.quantity || 0);
      const ordQty = new Decimal(orderRow.quantity || 0);
      const newQty = posQty.plus(ordQty);

      const posPrice = new Decimal(positionRow.enterPrice || 0);
      // const ordPrice = marketPrice;
      const ordPrice = new Decimal(orderRow.enterPrice);

      // const avgPrice = posPrice.mul(posQty).plus(ordPrice.mul(ordQty)).div(newQty);
      // const avgPrice = new Decimal(orderRow.enterPrice)

      // const newCost = new Decimal(positionRow.cost || 0).plus(orderRow.cost || 0);
      const newCost = new Decimal(newQty.mul(ordPrice))
      const newFee = new Decimal(positionRow.fee || 0).plus(orderRow.fee || 0);

      // 4. آپدیت پوزیشن
      positionRow.quantity = newQty.toFixed(12);
      positionRow.enterPrice = ordPrice.toFixed(12);
      positionRow.cost = newCost.toFixed(12);
      positionRow.fee = newFee.toFixed(12);
      positionRow.side = orderRow.side;
      positionRow.leverage = orderRow.leverage;
      positionRow.marginMode = orderRow.marginMode

      await manager.save(positionRow);

      // 5. حذف سفارش ادغام شده
      await manager.remove(orderRow);

      return positionRow;
    });
  }



  async closePositionTx(params: {
    position: FutureIOrderPayload;
    marketPrice: Decimal;
    exitType: FutureExitType;
  }): Promise<{
        status: boolean;
        position: FutureIOrderPayload;
      }> {
    const { position, marketPrice, exitType } = params;
    

    const price = new Decimal(marketPrice || 0);
    console.log("closePositionTx", position, marketPrice, exitType, price, )
    if (price.lte(0)) return {status: false, position: null};
    if (!position) return {status: false, position: null};
    const positionId = position.id
    return this.futureOrderRepository.manager.transaction(async (manager: EntityManager) => {
      const positionRow = await manager.findOne(FutureOrder, {
        where: { id: positionId },
        lock: { mode: "pessimistic_write" },
      });

      if (!positionRow){
        console.error("positionRow not found in closePositionTx")
        return {status: false, position: null};
      } 

      // اگر قبلاً بسته شده، idempotent => true
      if (positionRow.status === FutureOrderStatus.CLOSED){
        console.log(`position with this is ${positionId} not found in closePositionTx`)
        return {status: true, position: null};
      } 

      // فقط پوزیشن باز قابل بستن
      if (positionRow.status === FutureOrderStatus.PENDING){
        positionRow.fee = "0";
      //    console.log(`position with this is ${positionId} dos not have created status in closePositionTx`)
      //   return {status: false, position: null};
      } 

      const quantity = new Decimal(positionRow.quantity || 0);
      const enterPrice = new Decimal(positionRow.enterPrice || 0);
      // const leverage = new Decimal(positionRow.leverage || 0);
      const exitPrice = price;

      if (quantity.lte(0) || enterPrice.lte(0)) {
        console.log(`position with this id ${positionId} quantity or enter price not bigger than zero in closePositionTx`)
        return {status: false, position: null};
      }
      
      // let pnl: Decimal;
      // if (positionRow.side === FutureOrderSide.LONG) {
      //   pnl = exitPrice.minus(enterPrice).mul(quantity).mul(leverage);
      // } else if (positionRow.side === FutureOrderSide.SHORT) {
      //   pnl = enterPrice.minus(exitPrice).mul(quantity).mul(leverage);
      // } else {
      //   console.log(`position with this id ${positionId} dose not have valid side in closePositionTx`)
      //   return {status: false, position: null};
      // }

      // const fee = new Decimal(positionRow.fee || 0);
      // const netPnl = pnl.minus(fee);

      const now = new Date();

      positionRow.status = FutureOrderStatus.CLOSED;
      positionRow.exitPrice = exitPrice.toFixed(12);
      positionRow.pnl = position.pnl.toFixed(12);
      positionRow.closedAt = now;
      positionRow.exitType = exitType
      if(exitType === FutureExitType.LIQUID){
        positionRow.liquidationAt = now
      }
      // console.log("closePositionTx exitPrice", exitPrice, netPnl )

      // اگر تو Entity فیلد داری:
      // positionRow.closeReason = exitType;


      await manager.save(positionRow);
      const assetRes = await this.userAssetService.changeBalance(positionRow)
      await this.userTransactionService.store(positionRow, assetRes.asset_id, assetRes.currency_id, assetRes.amount.toNumber(),  "" )

      const futureAdlSetting = await this.futureAdlSettingService.findByBaseCurrency(positionRow.baseCurrencyId);

      if (futureAdlSetting) {
        // اگر منظور شما کسر کردن ۱۰ واحد از مقدار فعلی است:
        const currentAmount = new Decimal(futureAdlSetting.minTradeAmountUsdt || 0);
        const pnl = new Decimal(positionRow.pnl || 0);

        // مقدار جدید را محاسبه و به صورت string یا number ذخیره می‌کنیم
        futureAdlSetting.minTradeAmountUsdt = currentAmount.minus(pnl).toString(); 

        // ذخیره در دیتابیس (با فرض اینکه متد update یا save در سرویس شما پیاده شده است)
        await this.futureAdlSettingService.update(futureAdlSetting.id, {
          minTradeAmountUsdt: futureAdlSetting.minTradeAmountUsdt
        });
      }

      const finalPosition =  {
        ...position,
        status: positionRow.status,
        exit_price: positionRow.exitPrice,
        pnl: Number(positionRow.pnl),
        closed_at: positionRow.closedAt,
        exit_type: positionRow.exitType,
        liquidation_at: positionRow.liquidationAt,

        // pnl و liq_price معمولاً توسط موتور قیمت در گام بعدی دوباره محاسبه می‌شوند
      };
      return {status: true, position: finalPosition};
    });
  }





  public calculatePositionPnl(
    position: FutureIOrderPayload,
    marketPriceIn: Decimal.Value

    // side: FutureOrderSide;
    // quantity: Decimal.Value;
    // enterPrice: Decimal.Value;
    // marketPrice: Decimal.Value;
    // leverage?: Decimal.Value;
    // fee?: Decimal.Value;
  ): {
    pnl: Decimal;
    netPnl: Decimal;
  } {
    const side = position.side;
    const quantity = new Decimal(position.quantity || 0);
    const enterPrice = new Decimal(position.enter_price || 0);
    const marketPrice = new Decimal(marketPriceIn || 0);
    const leverage = new Decimal(position.leverage || 0);
    const fee = new Decimal(position.fee || 0);

    if (quantity.lte(0) || enterPrice.lte(0) || marketPrice.lte(0) || leverage.lte(0)) {
      return {
        pnl: new Decimal(0),
        netPnl: new Decimal(0),
      };
    }

    let pnl = new Decimal(0);

    if (side === FutureOrderSide.LONG) {
      pnl = marketPrice.minus(enterPrice).mul(quantity).mul(leverage);
    } else if (side === FutureOrderSide.SHORT) {
      pnl = enterPrice.minus(marketPrice).mul(quantity).mul(leverage);
    }

    return {
      pnl,
      netPnl: pnl.minus(fee),
    };
  }













}
