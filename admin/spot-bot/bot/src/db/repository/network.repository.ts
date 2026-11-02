import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { NetworkEntity } from "../entity/network.entity";

@Injectable()
export class NetworkRepository extends BaseRepository<NetworkEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(NetworkEntity, dataSource);
  }
}