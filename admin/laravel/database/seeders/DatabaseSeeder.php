<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([AdminSeeder::class]);
        $this->call([RolePermissionRouteNameSeeder::class]);
        $this->call([CountrySeeder::class]);
        $this->call([CountryUpdateSeeder::class]);
        // Currency Seeder
        $this->call([CurrencySeeder::class]);
        $this->call([NetworkSeeder::class]);
        $this->call(BankSeeder::class);
        $this->call(AuthOptionsSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(PatternOptionSeeder::class);
        $this->call(OptionSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(AddFiatCurrencySeeder::class);

        // Sample demo data (accounts + trades + report sections) is separate:
        // php artisan db:seed --class=DemoTradesSeeder
        // Individual: SpotOrderSeeder, FutureOrderSeeder, SwapSeeder, DemoAccountSeeder,
        // FishingTransactionSeeder, OpenBankTransactionSeeder, MarketingReportSeeder,
        // CurrencyDepositLogSeeder, ConvertedInventorySeeder, SupplierLogSeeder,
        // DepositRequestSeeder, StakingSeeder
    }
}
