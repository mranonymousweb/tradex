import { 
  Entity, PrimaryGeneratedColumn, Column, ManyToOne, JoinColumn} from 'typeorm';
import { UserEntity } from './user.entity';
import { BankEntity } from './bank.entity';
import { CurrencyEntity } from './currency.entity';
import { BaseEntity } from './base.entity';

@Entity('user_cards')
export class UserCardEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({name: 'user_id', nullable: true})
  userId?: number;

  @Column({name: 'bank_id', nullable: true})
  bankId?: number;

  @Column({name: 'currency_id', nullable: true})
  currencyId?: number;

  @Column({ nullable: true, name: 'card_owner' })
  cardOwner?: string;

  @Column({ nullable: true, name: 'card_number' })
  cardNumber?: string;

  @Column({ nullable: true })
  iban?: string;

  @Column({ type: 'text', default: 'waiting' }) 
  status: string;

  @Column({ type: 'text', nullable: true })
  description?: string;

  @Column({ nullable: true, name: 'account_number' })
  accountNumber?: string;

  @ManyToOne(() => UserEntity, { onDelete: 'CASCADE', onUpdate: 'CASCADE', 
    nullable: true 
  })
  @JoinColumn({ name: 'user_id' })
  user: UserEntity;

  @ManyToOne(() => BankEntity, { 
    onDelete: 'CASCADE', 
    onUpdate: 'CASCADE', 
    nullable: true 
  })
  @JoinColumn({ name: 'bank_id' })
  bank: BankEntity;

  @ManyToOne(() => CurrencyEntity, { 
    onDelete: 'SET NULL', 
    onUpdate: 'CASCADE', 
    nullable: true 
  })
  @JoinColumn({ name: 'currency_id' })
  currency: CurrencyEntity;
}