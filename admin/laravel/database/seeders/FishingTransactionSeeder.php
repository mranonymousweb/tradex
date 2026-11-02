<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Currency;
use App\Models\UserAsset;
use App\Models\UserCard;
use App\Models\UserTransaction;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;

class FishingTransactionSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $users = $this->demoTraders();
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);
        $irt = $this->requireCurrency(Currency::SYMBOL_IRT);
        $bank = Bank::query()->first();
        $card = UserCard::whereIn('user_id', collect($users)->pluck('id'))->first();

        UserTransaction::where('description', 'like', 'seeded-demo-fishing%')->delete();

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $user = $users[$i % count($users)];
            $useIrt = $i % 3 === 0;
            $currency = $useIrt ? $irt : $usdt;
            $amount = $useIrt ? (1_000_000 + $i * 50_000) : (10 + ($i % 50) * 1.5);
            $asset = UserAsset::where('user_id', $user->id)->where('currency_id', $currency->id)->first();

            UserTransaction::create([
                'asset_id' => $asset?->id,
                'user_id' => $user->id,
                'currency_id' => $currency->id,
                'bank_id' => $useIrt ? $bank?->id : null,
                'card_id' => $useIrt ? $card?->id : null,
                'amount' => $amount,
                'final_amount' => $amount,
                'fee' => 0,
                'txt_id' => 'SEED-FISHING-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'section' => $useIrt ? UserTransaction::SECTION_GATE : UserTransaction::SECTION_WALLET,
                'type' => $i % 2 === 0 ? UserTransaction::TYPE_DEPOSIT : UserTransaction::TYPE_WITHDRAW,
                'status' => UserTransaction::STATUS_FISHING,
                'gateway' => $useIrt ? UserTransaction::ZARINPAL_GATEWAY : null,
                'description' => 'seeded-demo-fishing',
                'address' => $useIrt ? null : 'TSeedFishingAddress' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
            ]);
        }

        $this->command?->info('Fishing transactions seeded: ' . self::DEMO_RECORD_COUNT);
    }
}
