<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\DepositRequest;
use App\Models\Network;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;
use RuntimeException;

class DepositRequestSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $users = $this->demoTraders();
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);
        $btc = $this->requireCurrency(Currency::SYMBOL_BTC);
        $network = Network::query()->first();

        if (!$network) {
            throw new RuntimeException('No network found. Run NetworkSeeder first.');
        }

        DepositRequest::whereIn('user_id', collect($users)->pluck('id'))->delete();

        $statuses = [
            DepositRequest::STATUS_WAITING,
            DepositRequest::STATUS_ACCEPT,
            DepositRequest::STATUS_REJECT,
        ];

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $user = $users[$i % count($users)];
            $currency = $i % 2 === 0 ? $usdt : $btc;
            $status = $statuses[$i % count($statuses)];
            $amount = $currency->symbol === Currency::SYMBOL_BTC
                ? round(0.01 + ($i % 25) * 0.005, 6)
                : round(50 + ($i % 40) * 12.5, 2);

            DepositRequest::create([
                'user_id' => $user->id,
                'network_id' => $network->id,
                'currency_id' => $currency->id,
                'amount' => $amount,
                'status' => $status,
                'symbol' => $currency->symbol,
                'change_at' => $status === DepositRequest::STATUS_WAITING
                    ? null
                    : now()->subHours(($i % 72) + 1),
            ]);
        }

        $this->command?->info('Deposit requests seeded: ' . self::DEMO_RECORD_COUNT);
    }
}
