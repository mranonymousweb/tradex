import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { MarketHistoryEntity } from "../entity/market-history.entity";

@Injectable()
export class MarketHistoryRepository extends BaseRepository<MarketHistoryEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(MarketHistoryEntity, dataSource);
  }
}