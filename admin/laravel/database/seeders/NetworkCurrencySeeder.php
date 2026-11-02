<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Network;
use App\Models\NetworkCurrency;
use Illuminate\Database\Seeder;

class NetworkCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currency = Currency::where('symbol', Currency::SYMBOL_USDT)->first();
        $networks = Network::all();

        foreach ($networks as $network) {
            NetworkCurrency::create([
                'currency_id'      => $currency->id,
                'network_id'       => $network->id,
                'fee'              => round(mt_rand(1, 100) / 1000, 3), // 0.001 - 0.1
                'is_withdraw_able' => true,
                'is_deposit_able'  => true,
                'type'             => 2,
                'min_deposit'      => mt_rand(50, 60),
                'max_withdraw'     => mt_rand(50, 60),
                'min_withdraw'     => mt_rand(50, 60),
                'status'           => 1,
            ]);
        }
    }
}
