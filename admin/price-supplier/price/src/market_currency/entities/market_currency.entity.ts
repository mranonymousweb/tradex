import { Column, Entity, PrimaryGeneratedColumn } from 'typeorm';

@Entity({ name: 'market_currencies' })
export class MarketCurrency {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({ name: 'pairs', length: 100 })
    pairs: string;

    @Column({ name: 'base_currency_id' })
    base_currency_id: number;

    @Column({ name: 'main_currency_id' })
    main_currency_id: number;

    @Column({ name: 'base_currency_symbol' })
    base_currency_symbol: string;

    @Column({ name: 'type' })
    type: number;

    @Column({ name: 'status' })
    status: number;

    @Column({name: "purchasable"})
    purchasable: boolean

    @Column({name: "sellable"})
    sellable: boolean

    @Column({name: "min_trade_amount_usdt"})
    min_trade_amount_usdt: number

    @Column({name: "increase_percent"})
    increase_percent: number

    @Column({name: "increase_value"})
    increase_value: number

    @Column({name: "decrease_percent"})
    decrease_percent: number

    @Column({name: "decrease_value"})
    decrease_value: number

    
}
