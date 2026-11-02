<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\MarketingCode;
use App\Models\MarketingLog;
use App\Models\Swap;
use App\Models\User;
use App\Models\UserMarketingCode;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketingReportSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $users = $this->demoTraders();
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);
        $parent = $users[0];
        $children = array_slice($users, 1);

        foreach ($children as $child) {
            if ((int) $child->parent_id !== (int) $parent->id) {
                $child->update(['parent_id' => $parent->id]);
            }
        }

        $userIds = collect($users)->pluck('id');

        MarketingLog::where(function ($q) use ($userIds) {
            $q->whereIn('parent_id', $userIds)->orWhereIn('user_id', $userIds);
        })->delete();

        MarketingCode::whereIn('user_id', $userIds)
            ->where('name', 'like', 'seeded-demo%')
            ->delete();

        UserMarketingCode::whereIn('user_id', $userIds)
            ->where('code', 'like', 'SEEDUMC%')
            ->delete();

        $parentCode = MarketingCode::create([
            'user_id' => $parent->id,
            'code' => 'SEEDMKT' . strtoupper(Str::random(5)),
            'parent_percentage' => 70,
            'user_percentage' => 30,
            'is_active' => MarketingCode::IS_ACTIVE,
            'name' => 'seeded-demo-parent-code',
        ]);

        foreach ($children as $index => $child) {
            MarketingCode::create([
                'user_id' => $child->id,
                'code' => 'SEEDMKT' . strtoupper(Str::random(5)) . $index,
                'parent_percentage' => 60,
                'user_percentage' => 40,
                'is_active' => MarketingCode::IS_ACTIVE,
                'name' => 'seeded-demo-child-code-' . $index,
            ]);

            UserMarketingCode::updateOrCreate(
                ['user_id' => $child->id],
                [
                    'code' => 'SEEDUMC' . strtoupper(Str::random(6)) . $index,
                    'percentage' => 30,
                    'is_active' => UserMarketingCode::ACTIVE,
                ]
            );
        }

        $swaps = Swap::whereIn('user_id', collect($users)->pluck('id'))->limit(50)->get();

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $child = $children[$i % count($children)];
            $total = round(5 + ($i % 40) * 1.25, 4);
            $parentAmount = round($total * 0.7, 4);
            $userAmount = round($total - $parentAmount, 4);
            $swap = $swaps->get($i % max($swaps->count(), 1));

            MarketingLog::create([
                'user_id' => $child->id,
                'parent_id' => $parent->id,
                'marketing_code_id' => $parentCode->id,
                'section' => $i % 2 === 0 ? MarketingLog::SECTION_SWAP : MarketingLog::SECTION_STAKING,
                'currency_id' => $usdt->id,
                'symbol' => $usdt->symbol,
                'total_amount' => $total,
                'user_amount' => $userAmount,
                'parent_amount' => $parentAmount,
                'loggable_id' => $swap?->id,
                'loggable_type' => $swap ? Swap::class : null,
            ]);
        }

        $this->command?->info('Marketing reports seeded: ' . self::DEMO_RECORD_COUNT . ' logs');
    }
}
