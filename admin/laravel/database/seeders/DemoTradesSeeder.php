<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoTradesSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Accounts (users / cards / wallets / tx / tickets / notifications)
            DemoAccountSeeder::class,

            // Trades
            SpotOrderSeeder::class,
            FutureOrderSeeder::class,
            SwapSeeder::class,

            // Admin report demo data (100 records each)
            FishingTransactionSeeder::class,
            OpenBankTransactionSeeder::class,
            MarketingReportSeeder::class,
            CurrencyDepositLogSeeder::class,
            ConvertedInventorySeeder::class,
            SupplierLogSeeder::class,
            DepositRequestSeeder::class,
            StakingSeeder::class,
        ]);
    }
}
