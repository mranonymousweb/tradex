import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { PackageEntity } from "../entity/package.entity";

@Injectable()
export class PackageRepository extends BaseRepository<PackageEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(PackageEntity, dataSource);
  }
}