import { Column, Entity, PrimaryGeneratedColumn } from "typeorm";

@Entity("user_transactions")
export class UserTransaction {
    @PrimaryGeneratedColumn()
    id: number

    @Column({name: "wallet_id"})
    wallet_id: number

    @Column({name: "asset_id"})
    asset_id: number

    @Column({name: "user_id"})
    user_id: number

    @Column({name: "currency_id"})
    currency_id: number

    @Column({name: "network_id"})
    network_id: number

    @Column({name: "bank_id", nullable: true})
    bank_id: number

    @Column({name: "card_id", nullable: true})
    card_id: number

    @Column({name: "amount", nullable: true})
    amount: number

    @Column({name: "txt_id", nullable: true})
    txt_id: string

    @Column({name: "section", nullable: true})
    section: number

    @Column({name: "type", nullable: true})
    type: number

    @Column({name: "status", nullable: true})
    status: number

    @Column({name: "description", nullable: true})
    description: string

    @Column({name: "gateway", nullable: true})
    gateway: string

    @Column({name: "sheba", nullable: true})
    sheba: string

    @Column({name: "address", nullable: true})
    address: string

    @Column({name: "tag", nullable: true})
    tag: string

    @Column({name: "fee", nullable: true})
    fee: number

    @Column({name: "final_amount", nullable: true})
    final_amount: number


    @Column({name: "transactionable_type", nullable: true})
    transactionable_type: string

    @Column({name: "transactionable_id", nullable: true})
    transactionable_id: number


    @Column({
      name: 'created_at',
      type: 'timestamp',
      nullable: true,
      default: () => 'CURRENT_TIMESTAMP',
    })
    created_at: Date;

    @Column({
      name: 'updated_at',
      type: 'timestamp',
      nullable: true,
      default: () => 'CURRENT_TIMESTAMP',
    })
    updated_at: Date;


    
}


export enum UserTransactionType {
  DEPOSIT = 1, // یا هر عددی که در دیتابیس لاراول ذخیره می‌کردید
  WITHDRAW = 2,
}

export enum UserTransactionSection {
  WALLET = 1,  // بخش کیف پول
  FUTURE = 2,  // بخش فیوچرز (در صورت نیاز)
}

export enum UserTransactionStatus {
  PENDING = 1,
  CONFIRMED = 2,
  REJECTED = 3,
}