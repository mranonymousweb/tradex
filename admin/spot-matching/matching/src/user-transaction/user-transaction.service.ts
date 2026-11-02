import { Injectable } from '@nestjs/common';
import { CreateUserTransactionDto } from './dto/create-user-transaction.dto';
import { UpdateUserTransactionDto } from './dto/update-user-transaction.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { UserTransaction, UserTransactionSection, UserTransactionStatus, UserTransactionType } from './entities/user-transaction.entity';
import { Repository } from 'typeorm';
import { FutureOrder } from 'src/future-order/entities/future-order.entity';

@Injectable()
export class UserTransactionService {

  constructor(
    @InjectRepository(UserTransaction)
    private readonly userTransactionRepository: Repository<UserTransaction>
  ){}



    /**
   * ذخیره تراکنش بر اساس سفارش فیوچرز
   */
  async store(
    futureOrder: FutureOrder, 
    assetId: number, 
    currencyId: number, 
    amount: number, 
    description: string
  ) {
    console.log("user transaction created with ", amount)
    const now = new Date();

    // ایجاد یک نمونه جدید از کلاس انتیتی
    const transaction = this.userTransactionRepository.create({
      asset_id: assetId,
      user_id: futureOrder.userId, // با توجه به مدل فیلد userId در FutureOrder
      currency_id: currencyId,
      amount: amount,
      type: UserTransactionType.DEPOSIT,
      section: UserTransactionSection.WALLET,
      
      // پیاده سازی معادل Polymorphic لاراول در فیلد transactionable
      // اگر در دیتابیس فیلدهای مجزا ندارید، می‌توانید نام انتیتی و آی‌دی را ترکیب کنید:
      transactionable_type:  `App\\Models\\FeatureOrder`,
      transactionable_id: futureOrder.id,
      
      description: description,
      status: UserTransactionStatus.CONFIRMED,

      created_at: now,
      updated_at: now,

    });

    // ذخیره در دیتابیس
    return await this.userTransactionRepository.save(transaction);
  }


    // 
        // UserTransaction::create([
        //     'asset_id' => $asset->id,
        //     'user_id' => $user->id,
        //     'currency_id' => $asset->currency_id,
        //     'amount' => $amount,
        //     'type' => UserTransaction::TYPE_DEPOSIT,
        //     'section' => UserTransaction::SECTION_WALLET,
        //     'transactionable_id' => $morph->getKey(),
        //     'transactionable_type' => $morph::class,
        //     'description' => $message,
        //     'status' => UserTransaction::STATUS_CONFIRMED,
        // ]);
  

  create(createUserTransactionDto: CreateUserTransactionDto) {
    return 'This action adds a new userTransaction';
  }

  findAll() {
    return `This action returns all userTransaction`;
  }

  findOne(id: number) {
    return `This action returns a #${id} userTransaction`;
  }

  update(id: number, updateUserTransactionDto: UpdateUserTransactionDto) {
    return `This action updates a #${id} userTransaction`;
  }

  remove(id: number) {
    return `This action removes a #${id} userTransaction`;
  }
}
