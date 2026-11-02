import { Entity, PrimaryGeneratedColumn, Column, OneToMany } from 'typeorm';
import { UserAssetEntity } from './user-asset.entity';
import { BaseEntity } from './base.entity';

@Entity('currencies')
export class CurrencyEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  symbol: string;

  @Column({ nullable: true })
  icon?: string;

  @Column({ type: 'smallint', default: 1, name: 'price_receiver', comment: '1:Binance, 2:Gateio, 3:Coinex, 4:Bingx, 5:Manual' })
  priceReceiver: number;

  @Column({ type: 'jsonb', nullable: true, comment: 'Exchanges like [Gateio, Binance, Coinex, Bingx]' })
  exchanges?: any;

  @Column({ type: 'decimal', precision: 15, scale: 8, default: '0', comment: 'Currency Price In USDT' })
  price: string;

  @Column({ type: 'smallint', comment: '1:Crypto, 2:Fiat, 3:Other' })
  type: number;

  @Column({ type: 'smallint', default: 1, comment: '1:Active, 2:Inactive' })
  status: number;

  @Column({ type: 'int', nullable: true, comment: 'Rank in supplier' })
  rank?: number;

  @Column({ type: 'int', nullable: true, name: 'unique_id', comment: 'Currency ID in supplier' })
  uniqueId?: number;

  @Column({ nullable: true, comment: 'Currency slug in supplier' })
  slug?: string;

  @Column({ nullable: true, comment: 'Currency supplier LIKE gate io' })
  supplier?: string;

  @OneToMany(() => UserAssetEntity, (asset) => asset.currency)
  assets: UserAssetEntity[];
}