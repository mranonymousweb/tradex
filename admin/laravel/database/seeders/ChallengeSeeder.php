<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\ChallengeProperty;
use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Create Challenge record
            $challenge = Challenge::create([
                'step_number' => 3,
                'price' => 250,
                'discount' => 25,
                'marketer_percentage' => 5,
                'type' => Challenge::TYPE_NORMAL,
                'status' => true,
            ]);

            // Define translations for both locales
            $translations = [
                'fa' => [
                    Challenge::COLUMN_TITLE => 'عنوان چالش نمونه',
                    Challenge::COLUMN_DESCRIPTION => 'توضیح چالش نمونه',
                ],
                'en' => [
                    Challenge::COLUMN_TITLE => 'Sample Challenge Title',
                    Challenge::COLUMN_DESCRIPTION => 'Sample challenge description',
                ],
            ];

            // Loop through locales and insert/update translations
            foreach ($translations as $locale => $fields) {
                foreach ($fields as $key => $value) {
                    $challenge->translations()->updateOrCreate(
                        ['key' => $key, 'locale' => $locale],
                        ['value' => $value]
                    );
                }
            }

            $this->generateChallengeProperty($challenge->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
    public function generateChallengeProperty($challengeId)
    {
        try {

            for ($step = 1; $step <= 3; $step++) {
                $rule = ChallengeProperty::create([
                    'challenge_id' => $challengeId,
                    'step' => $step,
                    'status' => true,
                    'initial_balance' => 1000 * $step,
                    'challenge_duration' => 30 + ($step * 5),
                    'min_day' => rand(10, 30),
                    'profit_target' => 10 + $step,
                    'daily_drawdown' => 5 + $step,
                    'max_drawdown' => 12 + $step,
                    'risk_reset_time' => '22:00:00',
                    'max_open_trades' => 5 + $step,
                    'max_position_size' => 2 + $step,
                    'currencies' => [Currency::SYMBOL_USDT, Currency::SYMBOL_TRX, Currency::SYMBOL_BTC],
                    'leverage' => 100 + (10 * $step),
                    'leverage_from' => 50,
                    'leverage_to' => 200,
                    'hold_overnight_positions' => true,
                    'weekend_trading_allowed' => false,
                    'force_sl_tp_usage' => true,
                    'auto_disable_on_rule_breach' => false,
                    'allow_copy_trading' => true,
                    'allow_bots_ea' => false,
                    'news_time_trading_block' => 1+$step,
                ]);

                $translations = [
                    'fa' => [
                        ChallengeProperty::COLUMN_TITLE => "قوانین چالش مرحله {$step}",
                        ChallengeProperty::COLUMN_DESCRIPTION => "این قوانین مربوط به مرحله {$step} از چالش هستند.",
                    ],
                    'en' => [
                        ChallengeProperty::COLUMN_TITLE => "Challenge Rules - Step {$step}",
                        ChallengeProperty::COLUMN_DESCRIPTION => "These rules apply to step {$step} of the challenge.",
                    ],
                ];

                foreach ($translations as $locale => $fields) {
                    foreach ($fields as $key => $value) {
                        $rule->translations()->updateOrCreate(
                            ['key' => $key, 'locale' => $locale],
                            ['value' => $value]
                        );
                    }
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
