import { PrimaryGeneratedColumn, Column, Entity} from 'typeorm';



export enum FutureOrderStatus {
  PENDING = 1,
  CREATED = 2,
  CLOSED = 3,
  DELETE = 4,
}

export enum FutureOrderType {
  MARKET = 1,       // TYPE_MARKET
  LIMIT = 2,        // TYPE_LIMIT
}

export enum FutureOrderSide {
  LONG = 1,  // SIDE_LONG
  SHORT = 2, // SIDE_SHORT
}

export enum FutureExitType {
  TP = 'tp',
  SL = 'sl',
  LIQUID = 'liquid',
  ADL = 'adl',
  CANCEL = 'cancel',
}

export enum FutureMarginMode {
  CROSS = 1,
  ISOLATE = 2,
}
@Entity('feature_orders')
export class FutureOrder {

      @PrimaryGeneratedColumn()
      id: number;
    
      @Column({ name: 'user_id' })
      userId: number;
    
      @Column({ name: 'main_currency_id' })
      mainCurrencyId: number;
    
      @Column({ name: 'base_currency_id' })
      baseCurrencyId: number;
    
      @Column({ name: 'main_currency_symbol' })
      mainCurrencySymbol: string;
    
      @Column({ name: 'base_currency_symbol' })
      baseCurrencySymbol: string;
    
      @Column()
      pairs: string;
    
      @Column({
        name: 'enter_balance',
        type: 'numeric',
        precision: 30,
        scale: 12,
        default: 0,
      })
      enterBalance: string;
    
      @Column({
        type: 'numeric',
        precision: 30,
        scale: 12,
      })
      quantity: string;
    
      @Column({
        name: 'is_robot',
        default: false,
      })
      isRobot: boolean;
    
      @Column({
        name: 'has_tpsl',
        default: false,
      })
      hasTpsl: boolean;
    
      @Column({
        name: 'take_profit',
        type: 'numeric',
        precision: 30,
        scale: 12,
        nullable: true,
      })
      takeProfit: string;
    
      @Column({
        name: 'stop_loss',
        type: 'numeric',
        precision: 30,
        scale: 12,
        nullable: true,
      })
      stopLoss: string;
    
      @Column({
        type: 'numeric',
        precision: 30,
        scale: 12,
      })
      price: string;
    
      @Column({
        name: 'stop_price',
        type: 'numeric',
        precision: 30,
        scale: 12,
        nullable: true,
      })
      stopPrice: string;
    
      @Column({
        name: 'enter_price',
        type: 'numeric',
        precision: 30,
        scale: 12,
      })
      enterPrice: string;
    
      @Column({
        name: 'exit_price',
        type: 'numeric',
        precision: 30,
        scale: 12,
        nullable: true,
      })
      exitPrice: string;
    
      @Column({
        name: 'liq_price',
        type: 'numeric',
        precision: 30,
        scale: 12,
      })
      liqPrice: string;
    
      @Column({
        type: 'numeric',
        precision: 30,
        scale: 12,
        default: 0,
      })
      fee: string;
    
      @Column({
        type: 'numeric',
        precision: 30,
        scale: 12,
      })
      cost: string;
    
      @Column({
        type: 'numeric',
        precision: 30,
        scale: 12,
        default: 0,
      })
      pnl: string;
    
      @Column({
        type: 'smallint',
      })
      side: number;
    
      @Column({
        type: 'smallint',
      })
      type: number;
    
      @Column({
        type: 'numeric',
        precision: 10,
        scale: 2,
      })
      leverage: string;
    
      @Column({
        name: 'margin_mode',
        type: 'smallint',
      })
      marginMode: number;
    
      @Column({
        name: 'opened_at',
        type: 'timestamp',
        nullable: true,
      })
      openedAt: Date;
    
      @Column({
        name: 'closed_at',
        type: 'timestamp',
        nullable: true,
      })
      closedAt: Date;
    
      @Column({
        name: 'liquidation_at',
        type: 'timestamp',
        nullable: true,
      })
      liquidationAt: Date;
    
      // @Column({
      //   name: 'exit_type',
      //   nullable: true,
      // })
      // exitType: string;

      // در کلاس Entity:
      @Column({
        name: 'exit_type',
        type: 'enum',          // نوع ستون را enum قرار می‌دهیم
        enum: FutureExitType,  // انام مجاز را معرفی می‌کنیم
        nullable: true,
      })
      exitType: FutureExitType | null; // تایپ آن را به خودِ Enum تغییر می‌دهیم
          
      @Column({
        type: 'smallint',
        default: FutureOrderStatus.PENDING,
      })
      status: number;

      @Column({
        name: 'created_at',
        type: 'timestamp',
        nullable: true,
      })
      created_at: Date;

    
}
