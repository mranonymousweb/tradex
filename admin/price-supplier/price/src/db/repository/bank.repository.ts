import { Injectable } from "@nestjs/common";
import { BaseRepository } from "./base.repository";
import { BankEntity } from "../entity/bank.entity";
import { DataSource } from "typeorm";
import { InjectDataSource } from "@nestjs/typeorm";

@Injectable()
export class BankRepository extends BaseRepository<BankEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(BankEntity, dataSource);
  }
}