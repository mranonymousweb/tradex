import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('currencies')
export class Currency {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({ name: 'symbol' })
    symbol?: string;


}
