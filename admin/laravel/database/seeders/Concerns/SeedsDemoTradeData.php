<?php

namespace Database\Seeders\Concerns;

use App\Models\Currency;
use App\Models\MarketCurrency;
use App\Models\Package;
use App\Models\User;
use App\Models\UserAsset;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

trait SeedsDemoTradeData
{
    protected const DEMO_TRADER_A_EMAIL = 'seed.trader.a@example.com';
    protected const DEMO_TRADER_B_EMAIL = 'seed.trader.b@example.com';
    protected const DEMO_USER_COUNT = 10;
    protected const DEMO_RECORD_COUNT = 100;

    protected function requireCurrency(string $symbol): Currency
    {
        $currency = Currency::where('symbol', $symbol)->first();

        if (!$currency) {
            throw new RuntimeException(
                "Currency {$symbol} not found. Run `php artisan db:seed` (CurrencySeeder) first."
            );
        }

        return $currency;
    }

    /**
     * @return User[]
     */
    protected function demoTraders(): array
    {
        $packageId = Package::query()->value('id');
        $users = [];

        for ($i = 1; $i <= self::DEMO_USER_COUNT; $i++) {
            $email = $this->demoTraderEmail($i);
            $users[] = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => 'Demo',
                    'family' => 'User ' . $i,
                    'mobile' => sprintf('0912%07d', 1000000 + $i),
                    'password' => Hash::make('password'),
                    'status' => User::STATUS_ACTIVE,
                    'country_id' => User::IRAN_COUNTRY_ID,
                    'package_id' => $packageId,
                    'email_verified_at' => now(),
                    'mobile_verified_at' => now(),
                    'address' => 'seeded-demo-user',
                ]
            );
        }

        return $users;
    }

    protected function demoTraderEmail(int $index): string
    {
        return match ($index) {
            1 => self::DEMO_TRADER_A_EMAIL,
            2 => self::DEMO_TRADER_B_EMAIL,
            default => "seed.trader.{$index}@example.com",
        };
    }

    protected function demoTraderIds(): Collection
    {
        return User::where('email', 'like', 'seed.trader.%')->pluck('id');
    }

    protected function ensureMarketPair(Currency $main, Currency $base, int $type): MarketCurrency
    {
        $pairs = "{$main->symbol}_{$base->symbol}";

        return MarketCurrency::updateOrCreate(
            ['pairs' => $pairs, 'type' => $type],
            [
                'main_currency_id' => $main->id,
                'base_currency_id' => $base->id,
                'main_currency_symbol' => $main->symbol,
                'base_currency_symbol' => $base->symbol,
                'status' => MarketCurrency::STATUS_ACTIVE,
                'purchasable' => true,
                'sellable' => true,
                'leverage' => 125,
                'min_trade_amount_usdt' => 10,
                'rank' => 1,
            ]
        );
    }

    protected function creditAsset(User $user, Currency $currency, float $balance, float $featureBalance = 0): UserAsset
    {
        return UserAsset::updateOrCreate(
            [
                'user_id' => $user->id,
                'currency_id' => $currency->id,
            ],
            [
                'symbol' => $currency->symbol,
                'balance' => $balance,
                'feature_balance' => $featureBalance,
                'block_balance' => 0,
                'margin_balance' => 0,
            ]
        );
    }

    /**
     * @param User[] $users
     * @param array<string, array{0: float, 1?: float}> $balancesBySymbol
     */
    protected function creditDemoAssets(array $users, array $balancesBySymbol): void
    {
        foreach ($users as $user) {
            foreach ($balancesBySymbol as $symbol => $amounts) {
                $this->creditAsset(
                    $user,
                    $this->requireCurrency($symbol),
                    $amounts[0],
                    $amounts[1] ?? 0
                );
            }
        }
    }
}
