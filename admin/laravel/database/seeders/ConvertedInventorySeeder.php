<?php

namespace Database\Seeders;

use App\Models\ConvertedInventory;
use App\Models\Currency;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;

class ConvertedInventorySeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $users = $this->demoTraders();
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);
        $btc = $this->requireCurrency(Currency::SYMBOL_BTC);
        $eth = $this->requireCurrency(Currency::SYMBOL_ETH);
        $irt = $this->requireCurrency(Currency::SYMBOL_IRT);

        ConvertedInventory::whereIn('user_id', collect($users)->pluck('id'))->delete();

        $currencies = [$usdt, $btc, $eth, $irt];
        $prices = [
            Currency::SYMBOL_USDT => 1,
            Currency::SYMBOL_BTC => 65000,
            Currency::SYMBOL_ETH => 3500,
            Currency::SYMBOL_IRT => 0.00001,
        ];

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $user = $users[$i % count($users)];
            $currency = $currencies[$i % count($currencies)];
            $amount = $currency->symbol === Currency::SYMBOL_IRT
                ? (100_000 + $i * 10_000)
                : round(0.01 + ($i % 30) * 0.015, 6);
            $price = $prices[$currency->symbol] ?? 1;
            $fee = round($amount * 0.001, 8);
            $total = round(($amount - $fee) * $price, 8);

            ConvertedInventory::create([
                'user_id' => $user->id,
                'currency_id' => $currency->id,
                'symbol' => $currency->symbol,
                'amount' => $amount,
                'fee' => $fee,
                'price' => $price,
                'total' => $total,
                'format' => $i % 4 === 0 ? ConvertedInventory::TYPE_ALL : ConvertedInventory::TYPE_SINGLE,
            ]);
        }

        $this->command?->info('Converted inventories seeded: ' . self::DEMO_RECORD_COUNT);
    }
}
