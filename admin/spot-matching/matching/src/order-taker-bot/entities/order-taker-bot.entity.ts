import {
  Column,
  CreateDateColumn,
  Entity,
  PrimaryGeneratedColumn,
  UpdateDateColumn,
} from 'typeorm';

export enum OrderTakerBotStatus {
  INACTIVE = 0,
  ACTIVE = 1,
}

@Entity('order_taker_bots')
export class OrderTakerBot {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'currency_id', type: 'int', nullable: true })
  currencyId: number | null;

  @Column({ type: 'varchar', length: 10, nullable: true })
  symbol: string | null;

  @Column({
    name: 'sellPercent',
    type: 'decimal',
    precision: 30,
    scale: 12,
  })
  sellPercent: string;

  @Column({
    name: 'buyPercent',
    type: 'decimal',
    precision: 30,
    scale: 12,
  })
  buyPercent: string;

  @Column({
    name: 'fillAmountPercentage',
    type: 'decimal',
    precision: 30,
    scale: 12,
    default: 0,
  })
  fillAmountPercentage: string;

  @Column({
    name: 'sendToContactor',
    type: 'boolean',
    default: false,
  })
  sendToContactor: boolean;

  @Column({
    type: 'int',
    default: OrderTakerBotStatus.ACTIVE,
  })
  status: OrderTakerBotStatus;

  @Column({ type: 'json', nullable: true })
  currencies: Record<string, any> | null;

  @Column({ type: 'int' })
  secount: number;

  @CreateDateColumn({ name: 'created_at', type: 'timestamp' })
  createdAt: Date;

  @UpdateDateColumn({ name: 'updated_at', type: 'timestamp' })
  updatedAt: Date;
}
