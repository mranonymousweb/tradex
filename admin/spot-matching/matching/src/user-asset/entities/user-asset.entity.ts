import { Column, Entity, PrimaryGeneratedColumn } from "typeorm";

@Entity("user_assets")
export class UserAsset {
    @PrimaryGeneratedColumn()
    id: number

    @Column({name: "currency_id"})
    currency_id: number

    @Column({name: "user_id"})
    user_id: number

    @Column({name: "symbol"})
    symbol: string

    @Column({name: "balance"})
    balance: number

    @Column({name: "feature_balance"})
    feature_balance: number

    @Column({name: "block_balance"})
    block_balance: number

    @Column({name: "margin_balance"})
    margin_balance: number
}
