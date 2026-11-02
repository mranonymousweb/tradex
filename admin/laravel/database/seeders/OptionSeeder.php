<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Services\Helper\Facade\Helper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            // Admin Key
            Option::ADMIN_LOGIN_URL => 'admin',

            //swap Options Key
            Option::SWAP_FEE => 1,
            Option::SWAP_MAX_FEE => 5,
            Option::SWAP_MARKETING_PERCENTAGE => 25,
            Option::SWAP_DECREASE_FROM_CURRENCY_PERCENTAGE => 0.5,
            Option::SWAP_MAX_DECREASE_FROM_CURRENCY_AMOUNT => 5,
            Option::SWAP_AUTOMATIC_SUPPLIER => Option::INACTIVE,
            Option::SWAP_WITHOUT_ADMIN => 150,
            Option::SWAP_MIN_AMOUNT => 3,


            //  Converted Inventory Configs
            Option::MIN_CONVERTED_INVENTORY => 5,
            Option::MAX_REQUEST_CONVERTED_INVENTORY => 2,
            Option::CONVERTED_INVENTORY_FEE => 1,
            Option::CONVERTED_INVENTORY_FEE_MAX => 5,


            //usdt config seeder
            Option::USDT_PRICE_TYPE => Option::USDT_AUTOMATIC_TYPE,
            Option::USDT_PRICE_DATA => Option::USDT_GLOBAL,
        ];
        foreach ($options as $key => $value) {
            Helper::saveOrUpdateOptions($key, $value, '');
        }

        // Meta Data Seeder

        $metaDataAll = [
            'fa' => [
                // General
                Option::META_DATA_TITLE => 'پلتفرم جامع  ارز دیجیتال آریا',
                Option::META_DATA_DESCRIPTION => 'پلتفرم جامع  ارز دیجیتال آریا.',
                Option::META_DATA_KEYWORDS => 'ارز دیجیتال,  آریا, بلاکچین, کریپتو',
                Option::META_DATA_AUTHOR => 'آریا گستران',
                Option::META_DATA_COPYRIGHT => 'تمامی حقوق متعلق به آریا گستران است.',
                Option::META_DATA_LOGO => 'assets/favicon/favicon-96x96.png',
                Option::META_DATA_FAVICON => 'assets/favicon/favicon.ico',
                Option::META_DATA_FOOTER => 'fa_footer',
                Option::META_DATA_FOOTER_LINK => 'fa_footer_link',
                Option::META_DATA_HEADER => 'fa_header',
                Option::META_DATA_HEADER_LINK => 'fa_header_link',
                Option::META_DATA_PHONE => '09900000000',
                Option::META_DATA_EMAIL => 'example@gmail.com',
                // Deposit
                Option::MATA_DATA_DEPOSIT_RULES_TITLE_1 => 'حداقل مقدار واریز به کیف پول',
                Option::MATA_DATA_DEPOSIT_RULES_URL_1 => '#',
                Option::MATA_DATA_DEPOSIT_RULES_TEXT_1 => 'حداقل مقدار واریز ارزی 10 تتر میباشد و حداقل مقدار واریز ریالی 100,000 ریال می باشد.',
                Option::MATA_DATA_DEPOSIT_RULES_TITLE_2 => 'آدرس و انتخاب شبکه',
                Option::MATA_DATA_DEPOSIT_RULES_URL_2 => '#',
                Option::MATA_DATA_DEPOSIT_RULES_TEXT_2 => 'درصورت ارسال به آدرس اشتباه تراکنش ارز دیجیتال قابل بازگشت نیست، هر ارز ممکنه روی چند شبکه باشه (مثل ERC20، TRC20، BEP20). ارسال روی شبکه اشتباه معمولاً باعث از دست رفتن کامل ارز میشه. شبکه‌ای که در کیف پول مقصد انتخاب میکنید باید با شبکه‌ای که از کیف پول مبدا ارسال میکنید مطابق باشد',
                Option::MATA_DATA_DEPOSIT_RULES_TITLE_3 => 'زمان تایید تراکنش',
                Option::MATA_DATA_DEPOSIT_RULES_URL_3 => '#',
                Option::MATA_DATA_DEPOSIT_RULES_TEXT_3 => 'بعضی شبکه‌ها سریع هستن (مثل TRC20)، بعضی‌ها کندتر (مثل BTC یا ERC20). امکان دارد از چند ثانیه تا چند دقیقه طول بکشد. تراکنش های ریالی از طریق درگاه بلافاصله انجام میشود اما تراکنش های کارت به کارت پس از تایید ادمین انجام میشود.',
                // Withdraw
                Option::MATA_DATA_WITHDRAW_RULES_TITLE_1 => 'حداقل مقدار برداشت',
                Option::MATA_DATA_WITHDRAW_RULES_URL_1 => '#',
                Option::MATA_DATA_WITHDRAW_RULES_TEXT_1 => 'حداقل برداشت ارز دیجیتال 10 تتر و حداقل برداشت ریالی 100,000 ریال می باشد.',
                Option::MATA_DATA_WITHDRAW_RULES_TITLE_2 => 'شبکه برداشت',
                Option::MATA_DATA_WITHDRAW_RULES_URL_2 => '#',
                Option::MATA_DATA_WITHDRAW_RULES_TEXT_2 => 'مطمئن شوید شبکه صحیح برای برداشت انتخاب شده است. انتخاب شبکه اشتباه می‌تواند باعث از دست رفتن کامل ارز شود.',
                Option::MATA_DATA_WITHDRAW_RULES_TITLE_3 => 'زمان پردازش برداشت',
                Option::MATA_DATA_WITHDRAW_RULES_URL_3 => '#',
                Option::MATA_DATA_WITHDRAW_RULES_TEXT_3 => 'برداشت‌های ارز دیجیتال ممکن است از چند ثانیه تا چند دقیقه طول بکشد. برداشت‌های ریالی توسط ادمین پردازش می‌شوند.',

            ],

            'en' => [
                Option::META_DATA_TITLE => 'Arya Digital Asset Platform',
                Option::META_DATA_DESCRIPTION => 'Comprehensive Arya digital asset platform.',
                Option::META_DATA_KEYWORDS => 'crypto, digital asset, Arya, blockchain',
                Option::META_DATA_AUTHOR => 'Arya Gostaran',
                Option::META_DATA_COPYRIGHT => 'All rights reserved by Arya Gostaran.',
                Option::META_DATA_LOGO => 'assets/favicon/favicon-96x96.png',
                Option::META_DATA_FAVICON => 'assets/favicon/favicon.ico',
                Option::META_DATA_FOOTER => 'en_footer',
                Option::META_DATA_FOOTER_LINK => 'en_footer_link',
                Option::META_DATA_HEADER => 'en_header',
                Option::META_DATA_HEADER_LINK => 'en_header_link',
                Option::META_DATA_PHONE => '09900000000',
                Option::META_DATA_EMAIL => 'example@gmail.com',
                // Deposit
                Option::MATA_DATA_DEPOSIT_RULES_TITLE_1 => 'Minimum Deposit Amount',
                Option::MATA_DATA_DEPOSIT_RULES_URL_1 => '#',
                Option::MATA_DATA_DEPOSIT_RULES_TEXT_1 => 'The minimum cryptocurrency deposit amount is 10 USDT, and the minimum fiat (IRR) deposit amount is 100,000 Rials.',

                Option::MATA_DATA_DEPOSIT_RULES_TITLE_2 => 'Address and Network Selection',
                Option::MATA_DATA_DEPOSIT_RULES_URL_2 => '#',
                Option::MATA_DATA_DEPOSIT_RULES_TEXT_2 => 'If funds are sent to the wrong address, the crypto transaction cannot be reversed. Each currency may support multiple networks (such as ERC20, TRC20, BEP20). Sending on the wrong network usually results in permanent loss of funds. The network selected in the destination wallet must match the one used in the source wallet.',

                Option::MATA_DATA_DEPOSIT_RULES_TITLE_3 => 'Transaction Confirmation Time',
                Option::MATA_DATA_DEPOSIT_RULES_URL_3 => '#',
                Option::MATA_DATA_DEPOSIT_RULES_TEXT_3 => 'Some networks are fast (like TRC20), while others are slower (like BTC or ERC20). Confirmation can take from a few seconds to several minutes. Rial transactions through the payment gateway are processed instantly, but card-to-card transactions are processed after admin approval.',

                // Withdraw
                Option::MATA_DATA_WITHDRAW_RULES_TITLE_1 => 'Minimum Withdrawal Amount',
                Option::MATA_DATA_WITHDRAW_RULES_URL_1 => '#',
                Option::MATA_DATA_WITHDRAW_RULES_TEXT_1 => 'The minimum withdrawal amount is 10 USDT for crypto and 100,000 Rials for fiat.',

                Option::MATA_DATA_WITHDRAW_RULES_TITLE_2 => 'Withdrawal Network',
                Option::MATA_DATA_WITHDRAW_RULES_URL_2 => '#',
                Option::MATA_DATA_WITHDRAW_RULES_TEXT_2 => 'Make sure to select the correct network for withdrawal. Choosing the wrong network can result in permanent loss of funds.',

                Option::MATA_DATA_WITHDRAW_RULES_TITLE_3 => 'Withdrawal Processing Time',
                Option::MATA_DATA_WITHDRAW_RULES_URL_3 => '#',
                Option::MATA_DATA_WITHDRAW_RULES_TEXT_3 => 'Crypto withdrawals may take from a few seconds to several minutes. Fiat withdrawals are processed manually by the admin.',

            ],
        ];

        foreach ($metaDataAll as $lang => $metaData) {
            foreach ($metaData as $key => $value) {
                Helper::saveOrUpdateOptions($key, $value, '', $lang);
            }
        }
    }
}
