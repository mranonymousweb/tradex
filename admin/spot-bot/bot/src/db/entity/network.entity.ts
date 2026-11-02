import { Entity, PrimaryGeneratedColumn, Column, OneToMany } from 'typeorm';
import { WalletRepositoryEntity } from './wallet-repository.entity';
import { UserTransactionEntity } from './user-transaction.entity';
import { BaseEntity } from './base.entity';

@Entity('networks')
export class NetworkEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  symbol: string;

  @Column({ nullable: true })
  icon?: string;

  @Column({ default: 1 })
  priority: number;

  @Column({ type: 'boolean', default: false, name: 'is_withdraw_able' })
  isWithdrawAble: boolean;

  @Column({ type: 'boolean', default: false, name: 'is_deposit_able' })
  isDepositAble: boolean;

  @Column({ type: 'boolean', default: false, name: 'has_tag' })
  hasTag: boolean;

  @Column({ type: 'smallint', default: 1, comment: '1:Active, 2:Inactive' })
  status: number;

  @Column({ nullable: true })
  supplier?: string;

  @OneToMany(() => WalletRepositoryEntity, (repo) => repo.network)
  walletRepositories: WalletRepositoryEntity[];

  @OneToMany(() => UserTransactionEntity, (tx) => tx.network)
  transactions: UserTransactionEntity[];
}