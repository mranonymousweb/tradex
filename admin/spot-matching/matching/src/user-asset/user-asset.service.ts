import { Injectable } from '@nestjs/common';
import { CreateUserAssetDto } from './dto/create-user-asset.dto';
import { UpdateUserAssetDto } from './dto/update-user-asset.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { UserAsset } from './entities/user-asset.entity';
import { privateDecrypt } from 'crypto';
import { Repository } from 'typeorm';
import { FutureIOrderPayload } from 'src/common/interface/future-order-payload.interface';
import Decimal from 'decimal.js';
import { FutureOrder } from 'src/future-order/entities/future-order.entity';

@Injectable()
export class UserAssetService {

  constructor(
    @InjectRepository(UserAsset)
    private readonly userAssetRepository: Repository<UserAsset>
  ){}

  create(createUserAssetDto: CreateUserAssetDto) {
    return 'This action adds a new userAsset';
  }

  findAll() {
    return `This action returns all userAsset`;
  }

  findOne(id: number) {
    return `This action returns a #${id} userAsset`;
  }

  update(id: number, updateUserAssetDto: UpdateUserAssetDto) {
    return `This action updates a #${id} userAsset`;
  }

  remove(id: number) {
    return `This action removes a #${id} userAsset`;
  }

  async findbyUserIdAndSymbol(user_id: number, symbol: string) {
    return await this.userAssetRepository.findOne({
      where: {
        user_id: user_id,
        symbol: symbol
      }
    });
  }

  async changeBalance(order: FutureOrder) {
    const userAsset = await this.findbyUserIdAndSymbol(order.userId, 'USDT');
    if (!userAsset) {
      throw new Error('User asset not found');
    }

    const currentBalance = new Decimal(userAsset.feature_balance ?? 0);
    const cost = new Decimal(order.cost ?? 0);
    const pnl = new Decimal(order.pnl ?? 0);
    const fee = new Decimal(order.fee ?? 0);

    // محاسبه بر اساس فرمول درخواستی: cost + pnl - fee
    // مرحله ۱: اعمال cost و pnl روی موجودی
    const balanceAfterTrade = currentBalance.plus(cost).plus(pnl);

    // مرحله ۲: کسر کارمزد با اعمال شرط عدم منفی شدن
    let finalBalance: Decimal;

    if (balanceAfterTrade.isNegative()) {
      // الف) اگر موجودی بعد از ترید منفی شده باشد، کارمزد بیشتری کسر نمی‌شود
      finalBalance = balanceAfterTrade;
    } else {
      // ب) کسر کارمزد با شرط عدم منفی شدن موجودی کل
      const balanceAfterFee = balanceAfterTrade.minus(fee);
      finalBalance = Decimal.max(balanceAfterFee, 0);
    }

    // محاسبه دقیق میزان تغییر کیف پول (مقدار تغییر یافته)
    const amountDifference = finalBalance.minus(currentBalance);

    userAsset.feature_balance = finalBalance.toNumber();

    await this.userAssetRepository.save(userAsset);

    return {
      asset_id: userAsset.id, 
      currency_id: userAsset.currency_id, 
      amount: amountDifference // ارسال به صورت عدد معمولی
    };
  }


}
