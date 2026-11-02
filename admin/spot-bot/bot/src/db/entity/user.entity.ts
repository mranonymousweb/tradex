import { 
  Entity, PrimaryGeneratedColumn, Column, ManyToOne, OneToMany, 
  JoinColumn, DeleteDateColumn 
} from 'typeorm';
import { UserAssetEntity } from './user-asset.entity';
import { PackageEntity } from './package.entity';
import { BaseEntity } from './base.entity';

@Entity('users')
export class UserEntity extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({name: 'package_id', nullable: true})
  packageId?: number;

  @Column({name: 'parent_id', nullable: true})
  parentId?: number;

  @Column({ length: 100 })
  name: string;

  @Column({ length: 100, nullable: true })
  family?: string;

  @Column({ length: 150, unique: true })
  email: string;

  @Column({ length: 20, unique: true })
  mobile: string;

  @Column({ length: 20, unique: true, nullable: true })
  tel?: string;

  @Column({ type: 'text', nullable: true })
  avatar?: string;

  @Column({ type: 'smallint', default: 1, comment: '1: active, 2: inactive' })
  status: number;

  @Column({ type: 'timestamp', nullable: true, name: 'email_verified_at' })
  emailVerifiedAt?: Date;

  @Column({ type: 'timestamp', nullable: true, name: 'mobile_verified_at' })
  mobileVerifiedAt?: Date;

  @Column({ nullable: true })
  password?: string;

  @Column({ type: 'int', nullable: true, name: 'country_id' })
  countryId?: number;

  @Column({ type: 'text', nullable: true })
  address?: string;

  @Column({ nullable: true, name: 'remember_token' })
  rememberToken?: string;

  @DeleteDateColumn({ name: 'deleted_at' })
  deletedAt: Date;

  @OneToMany(() => UserAssetEntity, (asset) => asset.user)
  assets: UserAssetEntity[];

  @ManyToOne(() => UserEntity, (user) => user.children, { onDelete: 'CASCADE', nullable: true })
  @JoinColumn({ name: 'parent_id' })
  parent: UserEntity;

  @OneToMany(() => UserEntity, (user) => user.parent)
  children: UserEntity[];

  @ManyToOne(() => PackageEntity, (pkg) => pkg.users, { onDelete: 'SET NULL', onUpdate: 'CASCADE', nullable: true })
  @JoinColumn({ name: 'package_id' })
  package: PackageEntity;
}