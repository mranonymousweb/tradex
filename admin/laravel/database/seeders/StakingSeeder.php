<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Staking;
use App\Models\UserAsset;
use App\Models\UserStaking;
use App\Models\UserTransaction;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;

class StakingSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $users = $this->demoTraders();
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);
        $btc = $this->requireCurrency(Currency::SYMBOL_BTC);
        $eth = $this->requireCurrency(Currency::SYMBOL_ETH);

        $this->creditDemoAssets($users, [
            Currency::SYMBOL_USDT => [50000],
            Currency::SYMBOL_BTC => [2],
            Currency::SYMBOL_ETH => [10],
        ]);

        $plans = $this->ensurePlans([
            [$usdt, 10, 100000, 7, 0.15, Staking::TYPE_DAILY],
            [$usdt, 50, 500000, 30, 0.25, Staking::TYPE_MONTHLY],
            [$btc, 0.001, 10, 14, 0.12, Staking::TYPE_WEEKLY],
            [$eth, 0.01, 100, 60, 0.18, Staking::TYPE_END],
        ]);

        $userIds = collect($users)->pluck('id');

        UserTransaction::whereIn('user_id', $userIds)
            ->whereIn('section', [
                UserTransaction::SECTION_STAKING,
                UserTransaction::SECTION_STAKING_PROFIT,
            ])
            ->where('description', 'like', 'seeded-demo-staking%')
            ->delete();

        UserStaking::whereIn('user_id', $userIds)->delete();

        $statuses = [
            UserStaking::STATUS_ACTIVE,
            UserStaking::STATUS_WETTING,
            UserStaking::STATUS_INACTIVE,
        ];

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $user = $users[$i % count($users)];
            $plan = $plans[$i % count($plans)];
            $status = $statuses[$i % count($statuses)];
            $days = (int) $plan->min_day + ($i % 20);
            $amount = $this->sampleAmount($plan, $i);
            $openedAt = now()->subDays(($i % 60) + 1)->subHours($i % 12);

            $userStaking = UserStaking::create([
                'user_id' => $user->id,
                'currency_id' => $plan->currency_id,
                'staking_id' => $plan->id,
                'amount' => $amount,
                'day' => $days,
                'daily_profit_percent' => $plan->daily_profit_percent,
                'type' => $plan->type,
                'status' => $status,
                'created_at' => $openedAt,
                'updated_at' => $status === UserStaking::STATUS_INACTIVE
                    ? $openedAt->copy()->addDays(max(1, (int) ($days / 2)))
                    : $openedAt->copy()->addHours(2),
            ]);

            $asset = UserAsset::where('user_id', $user->id)
                ->where('currency_id', $plan->currency_id)
                ->first();

            UserTransaction::create([
                'asset_id' => $asset?->id,
                'user_id' => $user->id,
                'currency_id' => $plan->currency_id,
                'amount' => $amount,
                'final_amount' => $amount,
                'fee' => 0,
                'txt_id' => 'SEED-STK-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'section' => UserTransaction::SECTION_STAKING,
                'type' => UserTransaction::TYPE_WITHDRAW,
                'status' => $status === UserStaking::STATUS_WETTING
                    ? UserTransaction::STATUS_WAITING
                    : UserTransaction::STATUS_CONFIRMED,
                'description' => 'seeded-demo-staking-lock',
                'transactionable_id' => $userStaking->id,
                'transactionable_type' => UserStaking::class,
                'created_at' => $openedAt,
                'updated_at' => $openedAt,
            ]);

            if ($status === UserStaking::STATUS_ACTIVE || $status === UserStaking::STATUS_INACTIVE) {
                $profit = round($amount * ((float) $plan->daily_profit_percent / 100) * max(1, (int) ($days / 3)), 8);

                UserTransaction::create([
                    'asset_id' => $asset?->id,
                    'user_id' => $user->id,
                    'currency_id' => $plan->currency_id,
                    'amount' => $profit,
                    'final_amount' => $profit,
                    'fee' => 0,
                    'txt_id' => 'SEED-STK-PROFIT-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                    'section' => UserTransaction::SECTION_STAKING_PROFIT,
                    'type' => UserTransaction::TYPE_DEPOSIT,
                    'status' => UserTransaction::STATUS_CONFIRMED,
                    'description' => 'seeded-demo-staking-profit',
                    'transactionable_id' => $userStaking->id,
                    'transactionable_type' => UserStaking::class,
                    'created_at' => $openedAt->copy()->addDays(1),
                    'updated_at' => $openedAt->copy()->addDays(1),
                ]);
            }
        }

        $this->command?->info('User stakings seeded: ' . self::DEMO_RECORD_COUNT);
    }

    /**
     * @param array<int, array{0: Currency, 1: float, 2: float, 3: int, 4: float, 5: string}> $definitions
     * @return Staking[]
     */
    private function ensurePlans(array $definitions): array
    {
        $plans = [];

        foreach ($definitions as [$currency, $minAmount, $maxAmount, $minDay, $dailyProfit, $type]) {
            $plans[] = Staking::updateOrCreate(
                [
                    'currency_id' => $currency->id,
                    'type' => $type,
                    'min_day' => $minDay,
                ],
                [
                    'min_amount' => $minAmount,
                    'max_amount' => $maxAmount,
                    'daily_profit_percent' => $dailyProfit,
                    'accepted_by_admin' => true,
                    'status' => Staking::STATUS_ACTIVE,
                    'marketing' => 5,
                ]
            );
        }

        return $plans;
    }

    private function sampleAmount(Staking $plan, int $index): float
    {
        $min = (float) $plan->min_amount;
        $max = (float) $plan->max_amount;
        $span = max($max - $min, $min);
        $amount = $min + ($span * (($index % 20) / 20));

        return round(min($amount, $max), 8);
    }
}
