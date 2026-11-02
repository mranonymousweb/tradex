import { Entity, PrimaryGeneratedColumn, Column, OneToMany } from 'typeorm';
import { UserEntity } from './user.entity';
import { BaseEntity } from './base.entity';

@Entity('packages')
export class PackageEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ type: 'decimal', precision: 60, scale: 20, nullable: true, name: 'withdrawal_max' })
  withdrawalMax?: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name: 'withdrawal_min_fiat' })
  withdrawalMinFiat?: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name: 'deposit_max_fiat' })
  depositMaxFiat?: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name: 'deposit_min_fiat' })
  depositMinFiat?: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name: 'fee_fiat' })
  feeFiat?: string;

  @Column({ type: 'boolean', nullable: true })
  deposit?: boolean;

  @Column({ type: 'boolean', nullable: true })
  withdrawal?: boolean;

  @Column({ type: 'decimal', precision: 14, scale: 8, nullable: true, name: 'internal_transfer_fee' })
  internalTransferFee?: string;

  @Column({ type: 'decimal', precision: 14, scale: 8, nullable: true, name: 'currency_maker_fee' })
  currencyMakerFee?: string;

  @Column({ type: 'decimal', precision: 14, scale: 8, nullable: true, name: 'currency_taker_fee' })
  currencyTakerFee?: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name: 'currency_taker_fee_max' })
  currencyTakerFeeMax?: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name: 'currency_maker_fee_max' })
  currencyMakerFeeMax?: string;

  @Column({ type: 'decimal', precision: 14, scale: 8, nullable: true, name: 'fiat_maker_fee' })
  fiatMakerFee?: string;

  @Column({ type: 'decimal', precision: 14, scale: 8, nullable: true, name: 'fiat_taker_fee' })
  fiatTakerFee?: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name: 'fiat_taker_fee_max' })
  fiatTakerFeeMax?: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name: 'fiat_maker_fee_max' })
  fiatMakerFeeMax?: string;

  @Column({ default: 'inactive' }) 
  status?: string;

  @Column({ type: 'decimal', precision: 14, scale: 8, default: '0', name: 'amount_to_grant' })
  amountToGrant: string;

  @Column({ type: 'decimal', precision: 40, scale: 10, default: '0', name: 'marketing_profit' })
  marketingProfit: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name: 'currency_min_deposit' })
  currencyMinDeposit?: string;

  @Column({ type: 'decimal', precision: 40, scale: 15, nullable: true, name:'currency_min_withdrawal' })
  currencyMinWithdrawal?: string;

  @OneToMany(() => UserEntity, (user) => user.package)
  users: UserEntity[];
}