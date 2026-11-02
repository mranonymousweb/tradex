import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { UserWalletEntity } from "../entity/user-wallet.entity";

@Injectable()
export class UserWalletRepository extends BaseRepository<UserWalletEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(UserWalletEntity, dataSource);
  }
}