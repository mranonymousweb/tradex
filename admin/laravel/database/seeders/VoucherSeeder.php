<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $currencies = [
            [
                'symbol' => 'IRT',
                'rank' => 1,
                'price_receiver' => 1,
                'type' => Currency::TYPE_FIAT,
                'exchanges' => ['Binance', 'Gateio'],
                'names' => ['en' => 'IRT', 'fa' => 'ریال'],
            ],
//            [
//                'symbol' => 'BTC',
//                'rank' => 2,
//                'price_receiver' => 1,
//                'exchanges' => ['Binance', 'Coinex', 'Gateio'],
//                'names' => ['en' => 'Bitcoin', 'fa' => 'بیت کوین'],
//            ],
//            [
//                'symbol' => 'ETH',
//                'rank' => 3,
//                'price_receiver' => 2,
//                'exchanges' => ['Gateio', 'Binance', 'Coinex'],
//                'names' => ['en' => 'Ethereum', 'fa' => 'اتریوم'],
//            ],
//            [
//                'symbol' => 'BNB',
//                'rank' => 4,
//                'price_receiver' => 1,
//                'exchanges' => ['Binance'],
//                'names' => ['en' => 'Binance Coin', 'fa' => 'بایننس کوین'],
//            ],
//            [
//                'symbol' => 'BCH',
//                'rank' => 5,
//                'price_receiver' => 2,
//                'exchanges' => ['Gateio'],
//                'names' => ['en' => 'Bitcoin Cash', 'fa' => 'بیت‌کوین کش'],
//            ],
//            [
//                'symbol' => 'XRP',
//                'rank' => 6,
//                'price_receiver' => 2,
//                'exchanges' => ['Binance', 'Coinex'],
//                'names' => ['en' => 'Ripple', 'fa' => 'ریپل'],
//            ],
//            [
//                'symbol' => 'LTC',
//                'rank' => 7,
//                'price_receiver' => 2,
//                'exchanges' => ['Binance', 'Gateio'],
//                'names' => ['en' => 'Litecoin', 'fa' => 'لایت‌کوین'],
//            ],
//            [
//                'symbol' => 'TRX',
//                'rank' => 8,
//                'price_receiver' => 2,
//                'exchanges' => ['Binance'],
//                'names' => ['en' => 'TRON', 'fa' => 'ترون'],
//            ],
//            [
//                'symbol' => 'ETC',
//                'rank' => 9,
//                'price_receiver' => 2,
//                'exchanges' => ['Coinex', 'Gateio'],
//                'names' => ['en' => 'Ethereum Classic', 'fa' => 'اتریوم کلاسیک'],
//            ],
//            [
//                'symbol' => 'XLM',
//                'rank' => 10,
//                'price_receiver' => 2,
//                'exchanges' => ['Binance'],
//                'names' => ['en' => 'Stellar', 'fa' => 'استلار'],
//            ],
        ];

        foreach ($currencies as $data) {
            $currency = Currency::create([
                'symbol' => $data['symbol'],
                'icon' => 'general/images/coins/' . strtolower($data['symbol']) . '.png',
                'rank' => $data['rank'],
                'price_receiver' => $data['price_receiver'],
                'exchanges' => json_encode($data['exchanges']),
                'type' => $data['type'], // Crypto
                'status' => 1, // Active
            ]);

            foreach ($data['names'] as $locale => $name) {
                CurrencyOption::create([
                    'currency_id' => $currency->id,
                    'key' => Currency::COLUMN_NAME,
                    'value' => $name,
                    'locale' => $locale,
                ]);
            }
        }

    }
}
