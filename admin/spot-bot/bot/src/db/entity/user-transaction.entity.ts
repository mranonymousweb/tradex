import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { UserAssetEntity } from './user-asset.entity';
import { UserEntity } from './user.entity';
import { NetworkEntity } from './network.entity';
import { CurrencyEntity } from './currency.entity';
import { BankEntity } from './bank.entity';
import { UserCardEntity } from './user-card.entity';
import { BaseEntity } from './base.entity';
import { UserWalletEntity } from './user-wallet.entity';

@Entity('user_transactions')
export class UserTransactionEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({name: 'wallet_id', nullable: true})
  walletId?: number;

  @Column({name: 'asset_id', nullable: true})
  assetId?: number;

  @Column({name: 'user_id', nullable: true})
  userId?: number;

  @Column({name: 'currency_id', nullable: true})
  currencyId?: number;

  @Column({name: 'network_id', nullable: true})
  networkId?: number;

  @Column({name: 'bank_id', nullable: true})
  bankId?: number;

  @Column({name: 'card_id', nullable: true})
  cardId?: number;

  @Column({ name: 'transactionable_type', nullable: true })
  transactionableType?: string;

  @Column({ name: 'transactionable_id', type: 'bigint', nullable: true })
  transactionableId?: string;

  @Column({ type: 'decimal', precision: 32, scale: 12, nullable: true })
  amount?: string;

  @Column({ type: 'text', nullable: true, name: 'txt_id' })
  txtId?: string;

  @Column({ type: 'smallint', comment: '1: Gate Way, 2: Wallet' })
  section: number;

  @Column({ type: 'smallint', comment: '1: Deposit, 2: Withdraw' })
  type: number;

  @Column({ type: 'smallint', default: 1, comment: '1: Waiting, 2: Confirmed, 3: Rejected' })
  status: number;

  @Column({ type: 'text', nullable: true })
  description?: string;

  @Column({ nullable: true, comment: 'only use in irt section gateway => name of used gateway' })
  gateway?: string;

  @Column({ nullable: true })
  sheba?: string;

  @Column({ nullable: true, comment: 'wallet address destiny in currency withdraw' })
  address?: string;

  @Column({ nullable: true, comment: 'wallet tag destiny in currency withdraw' })
  tag?: string;

  @Column({ type: 'decimal', precision: 30, scale: 10, nullable: true })
  fee?: string;

  @Column({ type: 'decimal', precision: 32, scale: 12, nullable: true, name: 'final_amount' })
  finalAmount?: string;

  @ManyToOne(() => UserWalletEntity, { onDelete: 'CASCADE', nullable: true })
  @JoinColumn({ name: 'wallet_id' })
  userWallet: UserWalletEntity;

  @ManyToOne(() => UserAssetEntity, { onDelete: 'CASCADE', nullable: true })
  @JoinColumn({ name: 'asset_id' })
  asset: UserAssetEntity;

  @ManyToOne(() => UserEntity, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'user_id' })
  user: UserEntity;

  @ManyToOne(() => CurrencyEntity, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'currency_id' })
  currency: CurrencyEntity;

  @ManyToOne(() => NetworkEntity, { onDelete: 'CASCADE', nullable: true })
  @JoinColumn({ name: 'network_id' })
  network: NetworkEntity;

  @ManyToOne(() => BankEntity, { onDelete: 'SET NULL', nullable: true })
  @JoinColumn({ name: 'bank_id' })
  bank: BankEntity;

  @ManyToOne(() => UserCardEntity, { onDelete: 'SET NULL', nullable: true })
  @JoinColumn({ name: 'card_id' })
  card: UserCardEntity;
}