<?php

namespace Database\Seeders;

use App\Models\OpenBankTransactions;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;

class OpenBankTransactionSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $users = $this->demoTraders();

        OpenBankTransactions::where('description', 'like', 'seeded-demo-open-bank%')->delete();

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $user = $i % 4 === 0 ? null : $users[$i % count($users)];
            $amount = 500_000 + ($i * 25_000);
            $isDeposit = $i % 2 === 0;
            $date = now()->subHours(($i % 240) + 1);

            OpenBankTransactions::create([
                'user_id' => $user?->id,
                'type' => $isDeposit ? OpenBankTransactions::TYPE_DEPOSIT : OpenBankTransactions::TYPE_WITHDRAW,
                'amount' => $amount,
                'is_used' => $user !== null && $i % 3 === 0,
                'balance' => 10_000_000 + $i * 1000,
                'source_account' => sprintf('IR%024d', 2000000000000000000 + $i),
                'destination_account' => sprintf('IR%024d', 3000000000000000000 + $i),
                'transaction_trace' => 'SEED-OB-TRACE-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'transaction_date_time' => $date->toDateTimeString(),
                'transaction_date' => $date,
                'debit_amount' => $isDeposit ? 0 : $amount,
                'credit_amount' => $isDeposit ? $amount : 0,
                'description' => 'seeded-demo-open-bank',
                'transaction_identity' => 'SEED-OB-ID-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
            ]);
        }

        $this->command?->info('Open bank transactions seeded: ' . self::DEMO_RECORD_COUNT);
    }
}
