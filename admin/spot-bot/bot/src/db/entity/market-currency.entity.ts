import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn, DeleteDateColumn, Index, Unique } from 'typeorm';
import { CurrencyEntity } from './currency.entity';
import { BaseEntity } from './base.entity';

@Entity('market_currencies')
@Unique('three_columns_unique', ['mainCurrencyId', 'baseCurrencyId', 'pairs', 'type'])
@Index(['pairs', 'status', 'type'])
export class MarketCurrencyEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'main_currency_id', nullable: true })
  mainCurrencyId?: number;

  @Column({ name: 'base_currency_id', nullable: true })
  baseCurrencyId?: number;

  @Column({ name: 'main_currency_symbol', length: 50, nullable: true })
  mainCurrencySymbol?: string;

  @Column({ name: 'base_currency_symbol', length: 50, nullable: true })
  baseCurrencySymbol?: string;

  @Column({ length: 50, comment: 'Uppercase format: market-currency_base-currency e.g., BTC_USDT' })
  pairs: string;

  @Column({ type: 'smallint', default: 1, comment: '1: Spot, 2: Future' })
  type: number;

  @Column({ type: 'smallint', default: 1, comment: '1: Active, 2: Inactive' })
  status: number;

  @Column({ default: true })
  purchasable: boolean;

  @Column({ default: true })
  sellable: boolean;

  @Column({ type: 'smallint', default: 1 })
  leverage: number;

  @Column({ type: 'decimal', name: 'min_trade_amount_usdt', precision: 20, scale: 8, default: '0' })
  minTradeAmountUsdt: string;

  @Column({ type: 'decimal', name: 'increase_percent', precision: 20, scale: 8, default: '0' })
  increasePercent: string;

  @Column({ type: 'decimal', name: 'increase_value', precision: 20, scale: 8, default: '0' })
  increaseValue: string;

  @Column({ type: 'decimal', name: 'decrease_percent', precision: 20, scale: 8, default: '0' })
  decreasePercent: string;

  @Column({ type: 'decimal', name: 'decrease_value', precision: 20, scale: 8, default: '0' })
  decreaseValue: string;

  @DeleteDateColumn({ name: 'deleted_at' })
  deletedAt: Date;

  @ManyToOne(() => CurrencyEntity)
  @JoinColumn({ name: 'main_currency_id' })
  mainCurrency: CurrencyEntity;

  @ManyToOne(() => CurrencyEntity)
  @JoinColumn({ name: 'base_currency_id' })
  baseCurrency: CurrencyEntity;
}