import {
  Entity,
  PrimaryGeneratedColumn,
  Column,
  CreateDateColumn,
  UpdateDateColumn,
} from 'typeorm';
import { MarketOpeningHourDay } from 'src/common/enum/market-opening-hour.enum';

@Entity('market_opening_hours')
export class MarketOpeningHour {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({
    type: 'varchar',
    length: 20,
    unique: true,
  })
  day: MarketOpeningHourDay;

  @Column({ name: 'is_active', default: false })
  isActive: boolean;

  @Column({ name: 'open_time', type: 'time', nullable: true })
  openTime?: string;

  @Column({ name: 'close_time', type: 'time', nullable: true })
  closeTime?: string;

  @CreateDateColumn({ name: 'created_at' })
  createdAt: Date;

  @UpdateDateColumn({ name: 'updated_at' })
  updatedAt: Date;
}
