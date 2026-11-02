import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn, DeleteDateColumn, Index } from 'typeorm';
import { CurrencyEntity } from './currency.entity';
import { UserEntity } from './user.entity';
import { BaseEntity } from './base.entity';

@Entity('markets')
@Index('idx_user_pairs', ['userId', 'pairs'])
@Index('idx_user_status', ['userId', 'status'])
@Index('idx_user_side', ['userId', 'side'])
@Index('idx_user_type', ['userId', 'type'])
export class MarketEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'user_id', nullable: true })
  userId?: number;

  @Column({ name: 'main_currency_id', nullable: true })
  mainCurrencyId?: number;

  @Column({ name: 'base_currency_id', nullable: true })
  baseCurrencyId?: number;

  @Column({ name: 'main_currency_symbol', length: 50, nullable: true })
  mainCurrencySymbol?: string;

  @Column({ name: 'base_currency_symbol', length: 50, nullable: true })
  baseCurrencySymbol?: string;

  @Column({ length: 100 })
  pairs: string;

  @Column({ type: 'smallint', comment: '1:Buy ; 2:Sell' })
  side: number;

  @Column({ type: 'smallint', comment: '1:Market; 2:Limit; 3:StopLimit; 4:LimitMaker; 5:StopMarket' })
  type: number;

  @Column({ type: 'smallint', default: 1 })
  leverage: number;

  @Column({ type: 'decimal', precision: 32, scale: 16 })
  amount: string;

  @Column({ type: 'decimal', precision: 32, scale: 16, default: '0' })
  price: string;

  @Column({ type: 'decimal', name: 'avg_price', precision: 32, scale: 16, default: '0' })
  avgPrice: string;

  @Column({ type: 'decimal', precision: 32, scale: 16, default: '0' })
  total: string;

  @Column({ type: 'decimal', name: 'stop_price', precision: 32, scale: 16, default: '0' })
  stopPrice: string;

  @Column({ type: 'decimal', name: 'market_price', precision: 32, scale: 16, default: '0' })
  marketPrice: string;

  @Column({ type: 'decimal', name: 'filled_receive_amount', precision: 32, scale: 16, default: '0' })
  filledReceiveAmount: string;

  @Column({ type: 'decimal', name: 'filled_amount', precision: 32, scale: 16, default: '0' })
  filledAmount: string;

  @Column({ type: 'decimal', name: 'filled_total', precision: 32, scale: 16, default: '0' })
  filledTotal: string;

  @Column({ type: 'decimal', precision: 32, scale: 16 })
  fee: string;

  @Column({ type: 'decimal', name: 'fee_percent', precision: 20, scale: 8, default: '0' })
  feePercent: string;

  @Column({ type: 'smallint', default: 1 })
  status: number;

  @Column({ type: 'timestamp', name: 'open_at', nullable: true })
  openAt?: Date;

  @Column({ type: 'timestamp', name: 'done_at', nullable: true })
  doneAt?: Date;

  @Column({ type: 'timestamp', name: 'canceled_at', nullable: true })
  canceledAt?: Date;

  @DeleteDateColumn({ name: 'deleted_at' })
  deletedAt: Date;

  @ManyToOne(() => UserEntity)
  @JoinColumn({ name: 'user_id' })
  user: UserEntity;

  @ManyToOne(() => CurrencyEntity)
  @JoinColumn({ name: 'main_currency_id' })
  mainCurrency: CurrencyEntity;

  @ManyToOne(() => CurrencyEntity)
  @JoinColumn({ name: 'base_currency_id' })
  baseCurrency: CurrencyEntity;
}