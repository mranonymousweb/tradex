import { Module } from '@nestjs/common';
import { BankRepository } from './repository/bank.repository';
import { CurrencyRepository } from './repository/currency.repository';
import { MarketCurrencyRepository } from './repository/market-currency.repository';
import { MarketHistoryRepository } from './repository/market-history.repository';
import { MarketRobotRepository } from './repository/market-robot.repository';
import { MarketRepository } from './repository/market.repository';
import { NetworkRepository } from './repository/network.repository';
import { PackageRepository } from './repository/package.repository';
import { UserAssetRepository } from './repository/user-asset.repository';
import { UserCardRepository } from './repository/user-card.repository';
import { UserTransactionRepository } from './repository/user-transaction.repository';
import { UserWalletRepository } from './repository/user-wallet.repository';
import { UserRepository } from './repository/user.repository';
import { WalletRepositoryRepository } from './repository/wallet-repository.repository';
import { MongooseModule } from '@nestjs/mongoose';
import { Log, LogSchema } from './schema/log.schema';
import { LogRepository } from './repository/log.repository';

@Module({
    imports: [
        MongooseModule.forFeature([
            { name: Log.name, schema: LogSchema },
        ]),
    ],
    providers: [
        BankRepository,
        CurrencyRepository,
        MarketCurrencyRepository,
        MarketHistoryRepository,
        MarketRobotRepository,
        MarketRepository,
        NetworkRepository,
        PackageRepository,
        UserAssetRepository, 
        UserCardRepository, 
        UserTransactionRepository, 
        UserWalletRepository, 
        UserRepository, 
        WalletRepositoryRepository,
        LogRepository
    ],
    exports: [
        BankRepository,
        CurrencyRepository,
        MarketCurrencyRepository,
        MarketHistoryRepository,
        MarketRobotRepository,
        MarketRepository,
        NetworkRepository,
        PackageRepository,
        UserAssetRepository, 
        UserCardRepository, 
        UserTransactionRepository, 
        UserWalletRepository, 
        UserRepository, 
        WalletRepositoryRepository,
        LogRepository
    ],
})
export class DbModule {}
