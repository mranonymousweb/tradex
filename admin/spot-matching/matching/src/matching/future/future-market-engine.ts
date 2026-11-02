import { Injectable, Inject } from "@nestjs/common";
import Decimal from "decimal.js";
import { FutureIOrderPayload } from "src/common/interface/future-order-payload.interface";
import { RedisService } from "src/redis/redis.service";
import { FutureOrderValidationService } from "./services/future-order-validation.service";
import { FutureOrderActivationService } from "./services/future-order-activation.service";
import { FuturePositionMergeService } from "./services/futurePositionMergeService.service";
import { FutureCloseValidationService } from "./services/futureCloseValidationService";
import { FuturePositionCloseService } from "./services/futurePositionCloseService";
import { FutureOrderService } from "src/future-order/future-order.service";
import { FutureOrder, FutureOrderStatus, FutureExitType, FutureOrderType } from "src/future-order/entities/future-order.entity";
import { Kafka } from "kafkajs";
import { KAFKA_CLIENT } from "src/kafka/constant/kafka-client.constant";
import { AdlService } from "./services/AdlService";
import { MarketCurrencyService } from "src/market-currency/market-currency.service";
import { CurrencyService } from "src/currency/currency.service";
@Injectable()
export class FutureMarketEngine {

  private pair: string;
  private base_currency_id: number;
  private producer;

  private pendingOrders: FutureIOrderPayload[] = [];

  private positions: FutureIOrderPayload[] = [];
  private closeOrders: FutureIOrderPayload[] = [];

  private marketPrice = new Decimal(0);

  private processingOpen = false;
  private processingClose = false;

  private isCycleRunning = false;
  private priceInterval: NodeJS.Timeout | null = null;

  constructor(
    private readonly redisService: RedisService,

    private readonly orderValidationService: FutureOrderValidationService,

    private readonly orderActivationService: FutureOrderActivationService,

    private readonly positionMergeService: FuturePositionMergeService,

    private readonly closeValidationService: FutureCloseValidationService,

    private readonly positionCloseService: FuturePositionCloseService,

    private readonly futureOrderService: FutureOrderService,

    private readonly adlService: AdlService,

    private readonly marketCurrencyService: MarketCurrencyService,
    private readonly currencyService: CurrencyService,

    @Inject(KAFKA_CLIENT) private readonly kafka: Kafka,
  ) {
    this.producer = this.kafka.producer();
    this.producer.connect().then(() => {
      console.log("Kafka producer connected in constructor!");
    });
  }

  configure(pair: string) {
    this.pair = pair;
  }

  async init() {
    const symbol = this.pair.split('_').pop();
    const base_currency = await this.currencyService.findBySymbol(symbol)
    this.base_currency_id = base_currency.id

    await this.hydrateOrdersFromDb()
    this.startPriceProcessingLoop();
  }

  private startPriceProcessingLoop() {
    if (this.priceInterval) return;

    this.priceInterval = setInterval(() => {
      void this.runPriceCycle();
    }, 1000);
  }

  private async runPriceCycle() {
    if (this.isCycleRunning) return;
    this.isCycleRunning = true;

    try {
      await this.getRedisPrice();
      // await this.updateMarketPrice();
      await this.processOpenOrders();
      await this.processCloseOrders();
    } catch (error) {
      console.error('FutureMarketEngine runPriceCycle error:', error);
    } finally {
      this.isCycleRunning = false;
    }
  }

  private async hydrateOrdersFromDb(): Promise<void> {
      if (!this.pair) {
      throw new Error("FutureMarketEngine pair is not configured");
    }

    const [pendingOrders, openOrders, redisPrice] = await Promise.all([
      this.futureOrderService.getPendingOrders(this.pair),
      this.futureOrderService.getOpenOrders(this.pair),
      this.redisService.getFuturePairPrice(this.pair),
    ]);

    this.pendingOrders = pendingOrders.map(order =>
      this.mapFutureOrderToPayload(order),
    );

    this.positions = openOrders.map(order =>
      this.mapFutureOrderToPayload(order),
    );

    if (redisPrice?.price) {
      this.marketPrice = new Decimal(redisPrice.price);
    }
  }

  destroy() {
    this.pendingOrders = [];
    this.positions = [];
  }

  async processOrder(
    order: FutureIOrderPayload,
    type: string = "NEW",
  ) {

    switch (type) {

      case "NEW":
        await this.addPendingOrder(order);
        await this.publishUserOrders(order)

        break;

      case "CANCEL":
        await this.cancelOrder(order);
        
        break;
    }
  }

  async addPendingOrder(order: FutureIOrderPayload) {

    const exists = this.pendingOrders.find(
      x => x.id === order.id,
    );

    if (exists) return;

    this.pendingOrders.push(order);
    await this.processOpenOrders()
  }

  async cancelOrder(order: FutureIOrderPayload) {

    console.log("cancelOrder : ", order.id)
    order.pnl = 0
    let existsPending = this.pendingOrders.find(x => x.id === order.id);
    if (existsPending) {
      this.pendingOrders = this.pendingOrders.filter(x => x.id !== order.id);
    }

    let existsPos = this.positions.find(x => x.id === order.id);
    if (existsPos) {
      this.positions = this.positions.filter(x => x.id !== order.id);

       const pnl = await this.futureOrderService.calculatePositionPnl(order, this.marketPrice)
       order.pnl = pnl.netPnl.toNumber()
       
    }

    if(!existsPos && !existsPending){
      console.error("order not found for cancel:", order.id);
      return
    }
    const now = new Date();

    await this.positionCloseService.close(
            order,
            this.marketPrice,
            FutureExitType.CANCEL,
          );
    await this.publishClosedPosition(order)
  }

  private async processOpenOrders() {

    if (this.processingOpen) {
      return;
    }

    this.processingOpen = true;

    try {

      const remainingOrders: FutureIOrderPayload[] = [];

      for (const order of this.pendingOrders) {

        if(order.status != FutureOrderStatus.PENDING)
          continue;

        const validation =
          await this.orderValidationService
            .validateForActivation(
              order,
              this.marketPrice,
            );


        if (!validation.valid) {
          console.error("orderValidationService processOpenOrders", order.id, validation)

          remainingOrders.push(order);

          continue;
        }
      

        const userPosition =
          this.findPosition(
            order.user_id,
            order.pairs,
          );

        if (!userPosition) {

          const position =
            await this.orderActivationService.activate(
              order,
              this.marketPrice
            );

          if (position) {
            this.positions.push(position);
             await this.publishUserOrders(position);
          }

          continue;
        }

        const merged =
          await this.positionMergeService.merge(
            userPosition,
            order,
            this.marketPrice
          );

        if (!merged) {
          remainingOrders.push(order);
          await this.publishUserOrders(merged);
        }
        if(merged){
          await this.replacePosition(merged);
          order.status = FutureOrderStatus.DELETE;
          await this.publishUserOrders(order);
        }

      }

      this.pendingOrders = remainingOrders;

    } finally {

      this.processingOpen = false;
    }
  }

  private async processCloseOrders() {

    if (this.processingClose) {
      return;
    }
    this.processingClose = true;
    try {

      // پوزیشن‌هایی که دیگر CREATED نیستند از لیست باز حذف می‌شوند
      this.positions = this.positions.filter(
        (p) => p.status === FutureOrderStatus.CREATED,
      );

      const remainingPositions: FutureIOrderPayload[] = [];


      // manage open orders for close
      for (let position of this.positions) {

        // calulate pnl order
        position.market_price = this.marketPrice.toString()
        const pnl = await this.futureOrderService.calculatePositionPnl(position, this.marketPrice)
        position.pnl = pnl.netPnl.toNumber()
        this.publishUserOrders(position)

        // get close  type (reason)
        const exitType =
          await this.closeValidationService.getExitType(
            position,
            this.marketPrice,
          );
          console.log("exit type for "+ position.id + " is : ", exitType)

        // if exit type not found continue
        if (!exitType) {
          remainingPositions.push(position);
          continue;
        }


        const closed =
          await this.positionCloseService.close(
            position,
            this.marketPrice,
            exitType,
          );
          console.log("closed for "+ position.id + " is : ", closed)

        if (!closed.status) {
          remainingPositions.push(position);
        } else {
          await this.publishClosedPosition(closed.position);
        }
      }

      this.positions = remainingPositions;

      // manage for adl ////////////////

      const adlPositions = await this.adlService.getAdlPositionsToClose(this.positions, this.pair, this.base_currency_id);

      for (const position of adlPositions) {
        const forcedClose = await this.positionCloseService.close(
          position,
          this.marketPrice,
          FutureExitType.ADL,
        );

        if (forcedClose.status && forcedClose.position) {
          this.positions = this.positions.filter((p) => p.id !== position.id);
          await this.publishClosedPosition(forcedClose.position);
        }
      }


      // end of manage for adl /////////////////

    } finally {

      this.processingClose = false;
    }
  }

  private findPosition(
    userId: number,
    pair: string,
  ) {

    return this.positions.find(
      p =>
        p.user_id === userId &&
        p.pairs === pair,
    );
  }

  private mapFutureOrderToPayload(order: FutureOrder): FutureIOrderPayload {
        return {
            id: order.id,
            user_id: order.userId ?? undefined, // نام فیلد در entity: userId
            main_currency_symbol: order.mainCurrencySymbol ?? undefined,
            base_currency_symbol: order.baseCurrencySymbol ?? undefined,
            pairs: order.pairs,

            // quantity در payload و entity: هر دو string هستند.
            // اگر در entity به صورت 'numeric' ذخیره شده و string برمی‌گردد، همینطور استفاده می‌کنیم.
            quantity: order.quantity,

            // price در payload و entity: هر دو string هستند.
            price: order.price,

            // stop_price در payload هست، ولی در entity جداگانه stopPrice است.
            stop_price: order.stopPrice ?? "0", // اگر null باشد default "0"

            // enter_balance در payload هست، ولی در entity enterBalance است.
            enter_balance: order.enterBalance,

            // has_tpsl در payload هست، ولی در entity hasTpsl است.
            has_tpsl: order.hasTpsl,

            // take_profit در payload هست، ولی در entity takeProfit است.
            take_profit: order.takeProfit ?? "0", // اگر null باشد default "0"

            // stop_loss در payload هست، ولی در entity stopLoss است.
            stop_loss: order.stopLoss ?? "0", // اگر null باشد default "0"

            // enter_price در payload هست، ولی در entity enterPrice است.
            enter_price: order.enterPrice,

            // liq_price در payload هست، ولی در entity liqPrice است.
            liq_price: order.liqPrice,

            fee: order.fee ? parseFloat(order.fee) : null, // فیلد fee در entity string است، باید به number تبدیل شود
            cost: typeof order.cost === 'string' ? parseFloat(order.cost) : order.cost ?? null, // cost هم ممکن است string باشد

            side: order.side,
            type: order.type,

            // leverage در payload string | undefined است، در entity string است.
            leverage: order.leverage ? parseFloat(order.leverage) : undefined, // تبدیل به number

            margin_mode: order.marginMode, // نام فیلد در entity: marginMode

            // status در payload number است، در entity هم number است.
            status: order.status,

            // open_at در payload هست، ولی در entity openedAt است.
            open_at: order.openedAt ?? undefined,

            pnl: order.pnl ? parseFloat(order.pnl) : null, // فیلد pnl در entity string است، باید به number تبدیل شود

            created_at : order.created_at,
            closed_at : order.closedAt,
            liquidation_at: order.liquidationAt,
            exit_type: order.exitType
        };
  }

  private async getRedisPrice(){

    const redisPrice =
      await this.redisService.getFuturePairPrice(this.pair);

    if (!redisPrice?.price) {
      console.error("price for this pair not found " + this.pair)
      return;
    }

    this.marketPrice = new Decimal(redisPrice.price);
  }

  private replacePosition(position: FutureIOrderPayload): void {
    const index = this.positions.findIndex(
      (p) => p.id === position.id,
    );

    if (index === -1) {
      this.positions.push(position);
      return;
    }

    this.positions[index] = position;
  }

  private async publishOrderBook() {
  
    const mergeOrdrs = this.pendingOrders.concat(this.positions);
    const buyers = mergeOrdrs
    .filter(t => t.side === 1)
    .map(t => ({
      price: t.enter_price,
      amount: t.quantity,
      total: Number(t.quantity) * Number(t.enter_price),
      sum: t.quantity,
      side: t.side,
    }));

    const sellers = mergeOrdrs
    .filter(t => t.side === 2)
    .map(t => ({
      price: t.enter_price,
      amount: t.quantity,
      total: Number(t.quantity) * Number(t.enter_price),
      sum: t.quantity,
      side: t.side,
    }));


    // 
    const doneTrades = this.closeOrders.map(t=> ({
      price: t.enter_price,
      amount: t.quantity, // ❗ مستقیم، بدون toFixed خطرناک
      total: Number(t.quantity) * Number(t.enter_price),
      side: t.side,
      opened_at: t.closed_at,
    }))
  

    const payload = {
      timestamp: Date.now().toString(),
      last: (doneTrades.length) ? doneTrades.at(-1)?.price : this.marketPrice,
      buyers: buyers,
      sellers: sellers,
      done: doneTrades
    };
  
    await this.producer.send({
      topic: 'future-real-orders',
      messages: [
        { key: this.pair, value: JSON.stringify(payload) }
      ],
    });
  }
  
  private async publishUserOrders(order) {
  
      await this.publishOrderBook()

      const payload = {
        timestamp: Date.now().toString(),
        pair: this.pair,
        user_id: order.user_id,
        order: order
      };

      console.log("order: "+ order.id + " and status: " + order.status + "send for user by id" + order.user_id )
  
      await this.producer.send({
        topic: 'future-my-orders',
        messages: [
          {
            key: `${order.user_id.toString()}-${this.pair}`,
            value: JSON.stringify(payload),
          },
        ],
      });
    }

  addCloseOrder(order: FutureIOrderPayload) {

    const exists = this.closeOrders.find(
      x => x.id === order.id,
    );

    if (exists) return;

    this.closeOrders.push(order);
  }

  private async publishClosedPosition(position: FutureIOrderPayload) {
    await this.addCloseOrder(position);
    await this.publishUserOrders(position);
    // await this.sendCloseOrderToKafka(position);
  }


  async CloseAllOrders() {
    const ordersToCancel = [
      ...this.pendingOrders,
      ...this.positions,
    ];

    for (const order of ordersToCancel) {
      try {
        await this.cancelOrder(order);
      } catch (error) {
        console.error('CloseAllOrders cancel failed:', order.id, error);
      }
    }
  }



    



}

