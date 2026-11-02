<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\MarketCurrency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseCurrency = Currency::where('symbol', Currency::SYMBOL_USDT)->firstOrFail();

        MarketCurrency::insert(
            [
                'base_currency_id' => $baseCurrency->id,
                'base_currency_symbol' => $baseCurrency->symbol,
                'pairs' => $baseCurrency->id,
                'type' => MarketCurrency::TYPE_FEATURE,
                'status' => MarketCurrency::STATUS_ACTIVE,
            ]
        );

    }
}
