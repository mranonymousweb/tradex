import { Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn } from 'typeorm';
import { UserEntity } from './user.entity';
import { CurrencyEntity } from './currency.entity';
import { BaseEntity } from './base.entity';

@Entity('user_assets')
export class UserAssetEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({name: 'user_id', nullable: true})
  userId?: number;

  @Column({name: 'currency_id', nullable: true})
  currencyId?: number;

  @Column()
  symbol: string;

  @Column({ type: 'decimal', precision: 50, scale: 12, default: '0' })
  balance: string;

  @Column({ type: 'decimal', precision: 50, scale: 12, default: '0', name: 'feature_balance' })
  featureBalance: string;

  @Column({ type: 'decimal', precision: 50, scale: 12, default: '0', name: 'block_balance' })
  blockBalance: string;

  @Column({ type: 'decimal', precision: 50, scale: 12, default: '0', name: 'margin_balance' })
  marginBalance: string;

  @Column({ type: 'decimal', precision: 50, scale: 12, default: '0', name: 'stating_balance' })
  statingBalance: string;

  @ManyToOne(() => CurrencyEntity, (currency) => currency.assets, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'currency_id' })
  currency: CurrencyEntity;

  @ManyToOne(() => UserEntity, (user) => user.assets, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'user_id' })
  user: UserEntity;
}