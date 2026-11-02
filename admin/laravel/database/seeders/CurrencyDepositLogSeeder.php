<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyDepositLog;
use App\Models\Network;
use App\Models\WalletRepository;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;

class CurrencyDepositLogSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $users = $this->demoTraders();
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);
        $btc = $this->requireCurrency(Currency::SYMBOL_BTC);
        $network = Network::query()->first();

        CurrencyDepositLog::where('tx_id', 'like', 'SEED-CDL-%')->delete();

        $walletStatuses = [
            CurrencyDepositLog::WALLET_STATUS_WAITING,
            CurrencyDepositLog::WALLET_STATUS_ACTIVE,
            CurrencyDepositLog::WALLET_STATUS_WHITE,
            CurrencyDepositLog::WALLET_STATUS_BLACK,
        ];
        $validationStatuses = [
            CurrencyDepositLog::VALIDATION_STATUS_PENDING,
            CurrencyDepositLog::VALIDATION_STATUS_APPROVED,
            CurrencyDepositLog::VALIDATION_STATUS_ADMIN_REVIEW,
            CurrencyDepositLog::VALIDATION_STATUS_AML_PENDING,
            CurrencyDepositLog::VALIDATION_STATUS_REJECTED,
        ];
        $validationTypes = [
            CurrencyDepositLog::VALIDATION_TYPE_NONE,
            CurrencyDepositLog::VALIDATION_TYPE_WHITELIST,
            CurrencyDepositLog::VALIDATION_TYPE_BLACKLIST,
            CurrencyDepositLog::VALIDATION_TYPE_AML,
            CurrencyDepositLog::VALIDATION_TYPE_ADMIN,
        ];
        $suppliers = [
            WalletRepository::SUPPLIER_GATE_IO,
            WalletRepository::SUPPLIER_COINEX,
            WalletRepository::SUPPLIER_CREGIS,
            WalletRepository::SUPPLIER_MANUAL,
            WalletRepository::SUPPLIER_HD_WALLET,
        ];

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $currency = $i % 2 === 0 ? $usdt : $btc;
            $user = $i % 5 === 0 ? null : $users[$i % count($users)];
            $validationStatus = $validationStatuses[$i % count($validationStatuses)];
            $isUsed = $validationStatus === CurrencyDepositLog::VALIDATION_STATUS_APPROVED && $user !== null && $i % 2 === 0;

            CurrencyDepositLog::create([
                'user_id' => $user?->id,
                'tx_id' => 'SEED-CDL-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'from_address' => 'fromSeedDeposit' . str_pad((string) $i, 10, '0', STR_PAD_LEFT),
                'address' => 'toSeedDeposit' . str_pad((string) $i, 12, '0', STR_PAD_LEFT),
                'symbol' => $currency->symbol,
                'amount' => $currency->symbol === Currency::SYMBOL_BTC
                    ? round(0.001 + ($i % 20) * 0.0005, 8)
                    : round(20 + ($i % 50) * 3.5, 4),
                'is_used' => $isUsed,
                'wallet_status' => $walletStatuses[$i % count($walletStatuses)],
                'validation_status' => $validationStatus,
                'validation_type' => $validationTypes[$i % count($validationTypes)],
                'validation_note' => 'seeded-demo-currency-deposit',
                'validation_payload' => ['source' => 'seeder', 'index' => $i],
                'validated_at' => in_array($validationStatus, [
                    CurrencyDepositLog::VALIDATION_STATUS_APPROVED,
                    CurrencyDepositLog::VALIDATION_STATUS_REJECTED,
                ], true) ? now()->subHours(($i % 48) + 1) : null,
                'network' => $network?->symbol ?? 'TRC20',
                'tag' => $i % 7 === 0 ? 'seed-tag-' . $i : null,
                'supplier' => $suppliers[$i % count($suppliers)],
            ]);
        }

        $this->command?->info('Currency deposit logs seeded: ' . self::DEMO_RECORD_COUNT);
    }
}
