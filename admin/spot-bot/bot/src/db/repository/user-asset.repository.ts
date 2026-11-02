import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { UserAssetEntity } from "../entity/user-asset.entity";

@Injectable()
export class UserAssetRepository extends BaseRepository<UserAssetEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(UserAssetEntity, dataSource);
  }
}