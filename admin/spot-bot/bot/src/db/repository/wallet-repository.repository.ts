import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { WalletRepositoryEntity } from "../entity/wallet-repository.entity";

@Injectable()
export class WalletRepositoryRepository extends BaseRepository<WalletRepositoryEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(WalletRepositoryEntity, dataSource);
  }
}