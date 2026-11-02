import { Column, Entity, PrimaryGeneratedColumn } from "typeorm";

export enum FutureHistoryType {

    TYPE_MAIN = 1,
    PERIODIC_PROFIT = 2,
    TYPE_OTHER = 3
}

@Entity("feature_histories")
export class FutureHistory {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({name: "order_id"})
    order_id: number

    @Column({
      name: "fee",
      type: "numeric",
      precision: 30,
      scale: 12,
      default: 0,
    })
    fee: string

    @Column({name: "type", type: "smallint"})
    type: number

    @Column({name: "description", type: "text", nullable: true})
    description: string | null

    @Column({name: "meta", type: "text", nullable: true})
    meta: string | null


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
