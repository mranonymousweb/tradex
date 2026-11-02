import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('future_adl_settings')
export class FutureAdlSetting {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'base_currency_id', nullable: true })
  baseCurrencyId?: number;

  @Column({
    type: 'decimal',
    name: 'adl_min_exchange_profit_percent',
    precision: 20,
    scale: 8,
    default: '0',
    comment: 'Minimum exchange profit percent',
  })
  adlMinExchangeProfitPercent: string;

  @Column({
    type: 'decimal',
    name: 'min_trade_amount_usdt',
    precision: 20,
    scale: 8,
    default: '0',
  })
  minTradeAmountUsdt: string;
}
