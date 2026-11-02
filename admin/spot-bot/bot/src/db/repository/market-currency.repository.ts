import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { MarketCurrencyEntity } from "../entity/market-currency.entity";

@Injectable()
export class MarketCurrencyRepository extends BaseRepository<MarketCurrencyEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(MarketCurrencyEntity, dataSource);
  }
}