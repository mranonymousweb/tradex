import { Injectable } from "@nestjs/common";
import { InjectDataSource } from "@nestjs/typeorm";
import { DataSource } from "typeorm";
import { BaseRepository } from "./base.repository";
import { UserCardEntity } from "../entity/user-card.entity";

@Injectable()
export class UserCardRepository extends BaseRepository<UserCardEntity> {
  constructor(@InjectDataSource() private dataSource: DataSource) {
    super(UserCardEntity, dataSource);
  }
}