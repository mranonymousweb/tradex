import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { CurrencyEntity } from "../entity/currency.entity";

@Injectable()
export class CurrencyRepository extends BaseRepository<CurrencyEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(CurrencyEntity, dataSource);
  }
}