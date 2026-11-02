import { Column, Entity, PrimaryGeneratedColumn } from "typeorm";

@Entity({ name: 'supplier_exchanges' })

export class SupplierExchange {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({ name: 'name', length: 100 })
    name: string;
    
    @Column({ name: 'price_supplier', length: 100 })
    price_supplier: string;

    @Column({ name: 'supplier', length: 100 })
    supplier: string;
        
}
