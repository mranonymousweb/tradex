<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Market;
use App\Models\SupplierLog;
use App\Models\Swap;
use App\Models\WalletRepository;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;

class SupplierLogSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $users = $this->demoTraders();
        $userIds = collect($users)->pluck('id');
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);
        $btc = $this->requireCurrency(Currency::SYMBOL_BTC);

        SupplierLog::where('description', 'like', 'seeded-demo-supplier-log%')->delete();

        $markets = Market::whereIn('user_id', $userIds)->limit(50)->get();
        $swaps = Swap::whereIn('user_id', $userIds)->limit(50)->get();

        $suppliers = [
            WalletRepository::SUPPLIER_GATE_IO,
            WalletRepository::SUPPLIER_COINEX,
            WalletRepository::SUPPLIER_NOBITEX,
            WalletRepository::SUPPLIER_BYTICLE,
        ];
        $statuses = [
            SupplierLog::STATUS_WATCHING,
            SupplierLog::STATUS_CONFIRMED,
            SupplierLog::STATUS_REJECTED,
        ];
        $pairs = ['BTC_USDT', 'ETH_USDT', 'USDT_IRT'];

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $useMarket = $i % 2 === 0;
            $loggable = $useMarket
                ? ($markets->get($i % max($markets->count(), 1)) ?? null)
                : ($swaps->get($i % max($swaps->count(), 1)) ?? null);

            $amount = round(0.01 + ($i % 40) * 0.02, 6);
            $total = round($amount * (65000 - ($i % 20) * 10), 4);

            SupplierLog::create([
                'loggable_type' => $loggable ? get_class($loggable) : ($useMarket ? Market::class : Swap::class),
                'loggable_id' => $loggable?->id,
                'currency_id' => $i % 3 === 0 ? $btc->id : $usdt->id,
                'pairs' => $pairs[$i % count($pairs)],
                'supplier' => $suppliers[$i % count($suppliers)],
                'amount' => $amount,
                'total' => $total,
                'description' => 'seeded-demo-supplier-log',
                'meta' => json_encode([
                    'source' => 'seeder',
                    'index' => $i,
                    'side' => $i % 2 === 0 ? 'buy' : 'sell',
                ], JSON_UNESCAPED_UNICODE),
                'status' => $statuses[$i % count($statuses)],
            ]);
        }

        $this->command?->info('Supplier logs seeded: ' . self::DEMO_RECORD_COUNT);
    }
}
