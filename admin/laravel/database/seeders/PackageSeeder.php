<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Seed the packages table with 6 predefined tiers.
     */
    public function run(): void
    {
        \DB::table('translations')
            ->where('transable_type', Package::class)
            ->delete();
        Package::query()->delete();

//        Package::truncate();
        $packages = [
            [
                'fa' => 'سطح 1',
                'en' => 'level 1',
                'data' => [
                    'id' => 1,
                    'deposit' => false,
                    'withdrawal' => false,
                    'deposit_max_fiat' => 0,
                    'withdrawal_min_fiat' => 0,
                    'deposit_min_fiat' => 0,
                    'withdrawal_max' => 0,
                    'currency_maker_fee' => 0.04,
                    'currency_maker_fee_max' => 100000,
                    'currency_taker_fee' => 0.045,
                    'currency_taker_fee_max' => 150000,
                    'fiat_maker_fee' => 0.04,
                    'fiat_taker_fee' => 0.045,
                    'fiat_maker_fee_max' => 5,
                    'fiat_taker_fee_max' => 5,
                    'status' => Package::STATUS_ACTIVE,
                    'fee_fiat' => 0.6,
                    'internal_transfer_fee' => 0,
                    'currency_min_deposit' => 1,
                    'currency_min_withdrawal' => 1,
                    'withdraw_time_limit' => 72,
                    'withdraw_first_time_limit' => 2,
                ]
            ],
            [
                'fa' => 'سطح 2',
                'en' => 'level 2',
                'data' => [
                    'id' => 2,
                    'deposit' => true,
                    'withdrawal' => true,
                    'deposit_max_fiat' => 25000000,
                    'withdrawal_min_fiat' => 25000000,
                    'deposit_min_fiat' => 1000,
                    'withdrawal_max' => 250,
                    'currency_maker_fee' => 0.035,
                    'currency_maker_fee_max' => 10,
                    'currency_taker_fee' => 0.035,
                    'currency_taker_fee_max' => 10,
                    'fiat_maker_fee' => 0.035,
                    'fiat_taker_fee' => 0.035,
                    'fiat_maker_fee_max' => 5,
                    'fiat_taker_fee_max' => 5,
                    'marketing_profit' => 2,
                    'status' => Package::STATUS_ACTIVE,
                    'fee_fiat' => 0.5,
                    'internal_transfer_fee' => 0,
                    'currency_min_deposit' => 1,
                    'currency_min_withdrawal' => 1,
                    'withdraw_time_limit' => 48,
                    'withdraw_first_time_limit' => 1,
                ]
            ],
            [
                'fa' => 'سطح 3',
                'en' => 'level 3',
                'data' => [
                    'id' => 3,
                    'deposit' => true,
                    'withdrawal' => true,
                    'deposit_max_fiat' => 100000000,
                    'withdrawal_min_fiat' => 100000000,
                    'deposit_min_fiat' => 1000,
                    'withdrawal_max' => 1000,
                    'currency_maker_fee' => 0.025,
                    'currency_maker_fee_max' => 8,
                    'currency_taker_fee' => 0.025,
                    'currency_taker_fee_max' => 8,
                    'fiat_maker_fee' => 0.025,
                    'fiat_taker_fee' => 0.025,
                    'fiat_maker_fee_max' => 5,
                    'fiat_taker_fee_max' => 5,
                    'marketing_profit' => 10,
                    'status' => Package::STATUS_ACTIVE,
                    'fee_fiat' => 0.4,
                    'internal_transfer_fee' => 0,
                    'currency_min_deposit' => 1,
                    'currency_min_withdrawal' => 1,
                    'withdraw_time_limit' => 24,
                    'withdraw_first_time_limit' => 0,
                ]
            ],
            [
                'fa' => 'سطح 4',
                'en' => 'level 4',
                'data' => [
                    'id' => 4,
                    'deposit' => true,
                    'withdrawal' => true,
                    'deposit_max_fiat' => 200000000,
                    'withdrawal_min_fiat' => 200000000,
                    'deposit_min_fiat' => 1000,
                    'withdrawal_max' => 2000,
                    'currency_maker_fee' => 0.02,
                    'currency_maker_fee_max' => 6,
                    'currency_taker_fee' => 0.02,
                    'currency_taker_fee_max' => 6,
                    'fiat_maker_fee' => 0.02,
                    'fiat_taker_fee' => 0.02,
                    'fiat_maker_fee_max' => 5,
                    'fiat_taker_fee_max' => 5,
                    'amount_to_grant' => 1000,
                    'marketing_profit' => 20,
                    'fee_fiat' => 0.3,
                    'internal_transfer_fee' => 0,
                    'currency_min_deposit' => 1,
                    'currency_min_withdrawal' => 1,
                    'withdraw_time_limit' => 24,
                    'withdraw_first_time_limit' => 0,
                ]
            ],
            [
                'fa' => 'سطح 5',
                'en' => 'level 5',
                'data' => [
                    'id' => 5,
                    'deposit' => true,
                    'withdrawal' => true,
                    'deposit_max_fiat' => 400000000,
                    'withdrawal_min_fiat' => 400000000,
                    'deposit_min_fiat' => 1000,
                    'withdrawal_max' => 4000,
                    'currency_maker_fee' => 0.018,
                    'currency_maker_fee_max' => 4,
                    'currency_taker_fee' => 0.018,
                    'currency_taker_fee_max' => 4,
                    'fiat_maker_fee' => 0.018,
                    'fiat_taker_fee' => 0.018,
                    'fiat_maker_fee_max' => 5,
                    'fiat_taker_fee_max' => 5,
                    'amount_to_grant' => 5000,
                    'marketing_profit' => 30,
                    'fee_fiat' => 0.2,
                    'internal_transfer_fee' => 0,
                    'currency_min_deposit' => 1,
                    'currency_min_withdrawal' => 1,
                    'withdraw_time_limit' => 24,
                    'withdraw_first_time_limit' => 0,
                ]
            ],
            [
                'fa' => 'سطح 6',
                'en' => 'level 6',
                'data' => [
                    'id' => 6,
                    'deposit' => true,
                    'withdrawal' => true,
                    'deposit_max_fiat' => 800000000,
                    'withdrawal_min_fiat' => 800000000,
                    'deposit_min_fiat' => 1000,
                    'withdrawal_max' => 8000,
                    'currency_maker_fee' => 0.015,
                    'currency_maker_fee_max' => 4,
                    'currency_taker_fee' => 0.015,
                    'currency_taker_fee_max' => 4,
                    'fiat_maker_fee' => 0.015,
                    'fiat_taker_fee' => 0.015,
                    'fiat_maker_fee_max' => 5,
                    'fiat_taker_fee_max' => 5,
                    'amount_to_grant' => 25000,
                    'marketing_profit' => 60,
                    'fee_fiat' => 0.1,
                    'internal_transfer_fee' => 0,
                    'currency_min_deposit' => 1,
                    'currency_min_withdrawal' => 1,
                    'withdraw_time_limit' => 24,
                    'withdraw_first_time_limit' => 0,
                ]
            ],
        ];

        foreach ($packages as $package) {
            $model = Package::create($package['data']);
            $model->translations()->createMany([
                ['key' => 'name', 'value' => $package['fa'], 'locale' => 'fa'],
                ['key' => 'name', 'value' => $package['en'], 'locale' => 'en'],
            ]);
        }
    }
}
