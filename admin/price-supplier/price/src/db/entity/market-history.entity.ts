import { Column, DeleteDateColumn, Entity, Index, JoinColumn, ManyToOne, PrimaryGeneratedColumn } from "typeorm";
import { MarketEntity } from "./market.entity";
import { MarketRobotEntity } from "./market-robot.entity";
import { BaseEntity } from "./base.entity";

@Entity('market_histories')
export class MarketHistoryEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'market_id' })
  @Index()
  marketId: number;

  @Column({ name: 'sub_market_id', nullable: true })
  @Index()
  subMarketId?: number;

  @Column({ name: 'market_robot_id', nullable: true })
  @Index()
  marketRobotId?: number;

  @Column({ type: 'decimal', precision: 50, scale: 30, comment: 'final amount' })
  @Index()
  amount: string;

  @Column({ type: 'decimal', precision: 50, scale: 30, comment: 'final price' })
  @Index()
  price: string;

  @Column({ type: 'decimal', name: 'global_price', precision: 50, scale: 30 })
  @Index()
  globalPrice: string;

  @DeleteDateColumn({ name: 'deleted_at' })
  deletedAt: Date;

  @ManyToOne(() => MarketEntity, { onDelete: 'CASCADE' })
  @JoinColumn({ name: 'market_id' })
  market: MarketEntity;

  @ManyToOne(() => MarketEntity, { onDelete: 'SET NULL' })
  @JoinColumn({ name: 'sub_market_id' })
  subMarket: MarketEntity;

  @ManyToOne(() => MarketRobotEntity, { onDelete: 'SET NULL' })
  @JoinColumn({ name: 'market_robot_id' })
  robot: MarketRobotEntity;
}