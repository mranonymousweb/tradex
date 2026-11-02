import { table } from "console";
import { Column, Entity, PrimaryGeneratedColumn } from "typeorm";


@Entity("options")
export class Option {
    @PrimaryGeneratedColumn()
    id: number

    @Column({name: "key"})
    key: string

    @Column({name: "value"})
    value: string

    @Column({name: "lang"})
    lang: string

    @Column({name: "description", nullable: true})
    description: string
}
