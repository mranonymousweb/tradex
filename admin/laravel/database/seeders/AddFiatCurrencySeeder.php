<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyOption;
use Illuminate\Database\Seeder;

class AddFiatCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uniqueId = 1000000;

        $names = [
            'AED' => ['درهم امارات', 'United Arab Emirates Dirham'],
            'ARS' => ['پزوی آرژانتین', 'Argentine Peso'],
            'AUD' => ['دلار استرالیا', 'Australian Dollar'],
            'BDT' => ['تاکای بنگلادش', 'Bangladeshi Taka'],
            'BHD' => ['دینار بحرین', 'Bahraini Dinar'],
            'BRL' => ['رئال برزیل', 'Brazilian Real'],
            'CAD' => ['دلار کانادا', 'Canadian Dollar'],
            'CHF' => ['فرانک سوئیس', 'Swiss Franc'],
            'CLP' => ['پزوی شیلی', 'Chilean Peso'],
            'CNY' => ['یوان چین', 'Chinese Yuan'],
            'COP' => ['پزوی کلمبیا', 'Colombian Peso'],
            'CZK' => ['کرون جمهوری چک', 'Czech Koruna'],
            'DKK' => ['کرون دانمارک', 'Danish Krone'],
            'EGP' => ['پوند مصر', 'Egyptian Pound'],
            'EUR' => ['یورو', 'Euro'],
            'GBP' => ['پوند انگلیس', 'British Pound Sterling'],
            'HKD' => ['دلار هنگ کنگ', 'Hong Kong Dollar'],
            'HUF' => ['فورینت مجارستان', 'Hungarian Forint'],
            'IDR' => ['روپیه اندونزی', 'Indonesian Rupiah'],
            'INR' => ['روپیه هند', 'Indian Rupee'],
            'JPY' => ['ین ژاپن', 'Japanese Yen'],
            'KRW' => ['وون کره جنوبی', 'South Korean Won'],
            'KWD' => ['دینار کویت', 'Kuwaiti Dinar'],
            'KZT' => ['تنگه قزاقستان', 'Kazakhstani Tenge'],
            'MXN' => ['پزوی مکزیک', 'Mexican Peso'],
            'MYR' => ['رینگیت مالزی', 'Malaysian Ringgit'],
            'NGN' => ['نایرای نیجریه', 'Nigerian Naira'],
            'NOK' => ['کرون نروژ', 'Norwegian Krone'],
            'NZD' => ['دلار نیوزیلند', 'New Zealand Dollar'],
            'PHP' => ['پزوی فیلیپین', 'Philippine Peso'],
            'PKR' => ['روپیه پاکستان', 'Pakistani Rupee'],
            'PLN' => ['زلوتی لهستان', 'Polish Zloty'],
            'QAR' => ['ریال قطر', 'Qatari Riyal'],
            'RON' => ['لئوی رومانی', 'Romanian Leu'],
            'RUB' => ['روبل روسیه', 'Russian Ruble'],
            'SAR' => ['ریال عربستان', 'Saudi Riyal'],
            'SEK' => ['کرون سوئد', 'Swedish Krona'],
            'SGD' => ['دلار سنگاپور', 'Singapore Dollar'],
            'THB' => ['بات تایلند', 'Thai Baht'],
            'TRY' => ['لیر ترکیه', 'Turkish Lira'],
            'TWD' => ['دلار تایوان', 'New Taiwan Dollar'],
            'UAH' => ['گریونای اوکراین', 'Ukrainian Hryvnia'],
            'USD' => ['دلار آمریکا', 'United States Dollar'],
            'VND' => ['دانگ ویتنام', 'Vietnamese Dong'],
            'ZAR' => ['رند آفریقای جنوبی', 'South African Rand'],
        ];

        $rank = 25;
        foreach (Currency::FiatSymbols() as $symbol) {

            if (Currency::where('symbol', $symbol)->exists()) {
                continue;
            }
            $currency = Currency::create([
                'unique_id' => $uniqueId++,
                'symbol' => $symbol,
                'slug' => strtolower($symbol),
                'icon' => 'coin_icons/fiats.png',
                'rank' => $rank,
                'type' => Currency::TYPE_FIAT,
                'status' => Currency::STATUS_INACTIVE,
            ]);

            CurrencyOption::create([
                'currency_id' => $currency->id,
                'locale' => 'fa',
                'key' => 'name',
                'value' => $names[$symbol][0],
            ]);

            CurrencyOption::create([
                'currency_id' => $currency->id,
                'locale' => 'en',
                'key' => 'name',
                'value' => $names[$symbol][1],
            ]);
            $rank++;
        }
    }
}
