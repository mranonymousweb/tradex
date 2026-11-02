import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';
import { BaseEntity } from './base.entity';

@Entity('banks')
export class BankEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ nullable: true })
  code?: string;

  @Column({ nullable: true })
  img?: string;

  @Column({ type: 'text', nullable: true, default: 'active' })
  status?: string;
}