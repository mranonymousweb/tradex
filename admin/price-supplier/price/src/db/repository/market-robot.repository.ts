import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { MarketRobotEntity } from "../entity/market-robot.entity";

@Injectable()
export class MarketRobotRepository extends BaseRepository<MarketRobotEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(MarketRobotEntity, dataSource);
  }
}