import { Column, Entity, PrimaryGeneratedColumn } from 'typeorm';

@Entity({ name: 'currencies' })
export class Currency {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({ name: 'symbol', length: 100 })
    symbol: string;

    @Column({ name: 'type' })
    type: number;

    @Column({ name: 'status' })
    status: number;


    
}
