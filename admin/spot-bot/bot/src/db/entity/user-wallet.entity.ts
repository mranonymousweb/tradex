import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { WalletRepositoryEntity } from './wallet-repository.entity';
import { CurrencyEntity } from './currency.entity';
import { UserEntity } from './user.entity';
import { NetworkEntity } from './network.entity';
import { BaseEntity } from './base.entity';

@Entity('user_wallets')
export class UserWalletEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({name: 'user_id', nullable: true})
  userId?: number;

  @Column({name: 'network_id', nullable: true})
  networkId?: number;

  @Column({name: 'currency_id', nullable: true})
  currencyId?: number;

  @Column({name: 'wallet_id', nullable: true})
  walletId?: number;

  @Column()
  symbol: string;

  @Column({ type: 'text' })
  address: string;

  @Column({ type: 'text', nullable: true })
  tag?: string;

  @Column({ nullable: true })
  supplier?: string;

  @ManyToOne(() => WalletRepositoryEntity, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'wallet_id' })
  walletRepository: WalletRepositoryEntity;

  @ManyToOne(() => CurrencyEntity, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'currency_id' })
  currency: CurrencyEntity;

  @ManyToOne(() => NetworkEntity, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'network_id' })
  network: NetworkEntity;

  @ManyToOne(() => UserEntity, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'user_id' })
  user: UserEntity;
}