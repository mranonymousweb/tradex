import { Entity, PrimaryGeneratedColumn } from "typeorm";
import { BaseEntity } from "./base.entity";

@Entity('market_robots')
export class MarketRobotEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;
}