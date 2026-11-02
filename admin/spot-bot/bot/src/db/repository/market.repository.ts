import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { MarketEntity } from "../entity/market.entity";

@Injectable()
export class MarketRepository extends BaseRepository<MarketEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(MarketEntity, dataSource);
  }
}