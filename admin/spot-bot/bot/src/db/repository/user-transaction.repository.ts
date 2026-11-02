import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { UserTransactionEntity } from "../entity/user-transaction.entity";

@Injectable()
export class UserTransactionRepository extends BaseRepository<UserTransactionEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(UserTransactionEntity, dataSource);
  }
}