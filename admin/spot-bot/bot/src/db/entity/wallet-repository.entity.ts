import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { NetworkEntity } from './network.entity';
import { BaseEntity } from './base.entity';

@Entity('wallet_repositories')
export class WalletRepositoryEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({name: 'network_id', nullable: true})
  networkId?: number;

  @Column({ nullable: true, comment: 'network symbol' })
  symbol?: string;

  @Column({ type: 'text' })
  address: string;

  @Column({ type: 'text', nullable: true })
  tag?: string;

  @Column({ type: 'smallint', comment: '1: share, 2: dedicate' })
  type: number;

  @Column({ type: 'boolean', default: false, name: 'is_used' })
  isUsed: boolean;

  @Column({ type: 'smallint', default: 1, comment: '1: Active, 2: Inactive' })
  status: number;

  @Column({ nullable: true })
  supplier?: string;

  @Column({ type: 'bigint', nullable: true, comment: 'this field is used only for bitycle' })
  nounce?: string;

  @ManyToOne(() => NetworkEntity, (network) => network.walletRepositories, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'network_id' })
  network: NetworkEntity;
}