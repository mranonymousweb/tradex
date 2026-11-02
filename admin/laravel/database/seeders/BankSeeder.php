<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class BankSeeder extends Seeder
{
    public function run()
    {
        $date = now();

        $banks = [
            ['fa' => 'ملی', 'en' => 'Melli', 'code' => '017', 'img' => '/general/images/banks-logo/meli.png'],
            ['fa' => 'ملت', 'en' => 'Mellat', 'code' => '012', 'img' => '/general/images/banks-logo/mellat.png'],
            ['fa' => 'آینده', 'en' => 'Ayandeh', 'code' => '062', 'img' => '/general/images/banks-logo/ayandeh.png'],
            ['fa' => 'سپهر', 'en' => 'Sepah', 'code' => '015', 'img' => '/general/images/banks-logo/sepah.png'],
            ['fa' => 'صنعت و معدن', 'en' => 'Sanat va Madan', 'code' => '011', 'img' => '/general/images/banks-logo/sanat-va-madan.png'],
            ['fa' => 'کشاورزی', 'en' => 'Agricultural', 'code' => '016', 'img' => '/general/images/banks-logo/agri.png'],
            ['fa' => 'مسکن', 'en' => 'Maskan', 'code' => '014', 'img' => '/general/images/banks-logo/maskan.png'],
            ['fa' => 'صادرات', 'en' => 'Saderat', 'code' => '019', 'img' => '/general/images/banks-logo/saderat.png'],
            ['fa' => 'توسعه صادرات', 'en' => 'Tosee Saderat', 'code' => '020', 'img' => null],
            ['fa' => 'توسعه تعاون', 'en' => 'Tosee Taavon', 'code' => '022', 'img' => '/general/images/banks-logo/tosee-taavon.png'],
            ['fa' => 'پست بانک ایران', 'en' => 'Post Bank', 'code' => '021', 'img' => '/general/images/banks-logo/postbank.png'],
            ['fa' => 'رفاه', 'en' => 'Refah', 'code' => '013', 'img' => '/general/images/banks-logo/refah.png'],
            ['fa' => 'اقتصاد نوین', 'en' => 'Eghtesad Novin', 'code' => '055', 'img' => '/general/images/banks-logo/eghtesad-novin.png'],
            ['fa' => 'پارسیان', 'en' => 'Parsian', 'code' => '054', 'img' => '/general/images/banks-logo/parsian.png'],
            ['fa' => 'کارآفرین', 'en' => 'Karafarin', 'code' => '053', 'img' => '/general/images/banks-logo/karafarin.png'],
            ['fa' => 'سامان', 'en' => 'Saman', 'code' => '056', 'img' => '/general/images/banks-logo/saman.png'],
            ['fa' => 'سینا', 'en' => 'Sina', 'code' => '059', 'img' => '/general/images/banks-logo/sina.png'],
            ['fa' => 'خاور میانه', 'en' => 'Khavarmiane', 'code' => '078', 'img' => '/general/images/banks-logo/khavarmiane.png'],
            ['fa' => 'شهر', 'en' => 'Shahr', 'code' => '061', 'img' => '/general/images/banks-logo/shahr.png'],
            ['fa' => 'دی', 'en' => 'Day', 'code' => '066', 'img' => '/general/images/banks-logo/day.png'],
            ['fa' => 'تجارت', 'en' => 'Tejarat', 'code' => '018', 'img' => '/general/images/banks-logo/tejarat.png'],
            ['fa' => 'حکمت ایرانیان', 'en' => 'Hekmat Iranian', 'code' => '065', 'img' => '/general/images/banks-logo/hekmat.png'],
            ['fa' => 'ایران زمین', 'en' => 'Iran Zamin', 'code' => '069', 'img' => '/general/images/banks-logo/iranzamin.png'],
            ['fa' => 'قوامین', 'en' => 'Ghavamian', 'code' => '052', 'img' => '/general/images/banks-logo/ghavamin.png'],
            ['fa' => 'انصار', 'en' => 'Ansar', 'code' => '063', 'img' => '/general/images/banks-logo/ansar.png'],
            ['fa' => 'سرمایه', 'en' => 'Sarmayeh', 'code' => '058', 'img' => '/general/images/banks-logo/sarmayeh.png'],
            ['fa' => 'پاسارگاد', 'en' => 'Pasargad', 'code' => '057', 'img' => '/general/images/banks-logo/pasargad.png'],
            ['fa' => 'قرض‌الحسنه مهر ایران', 'en' => 'Mehr Iran', 'code' => '060', 'img' => '/general/images/banks-logo/mehr.png'],
            ['fa' => 'قرض‌الحسنه رسالت', 'en' => 'Resalat', 'code' => '070', 'img' => '/general/images/banks-logo/resalat.png'],
            ['fa' => 'موسسه اعتباری نور', 'en' => 'Noor Credit', 'code' => '080', 'img' => null],
            ['fa' => 'موسسه اعتباری کوثر', 'en' => 'Kosar Credit', 'code' => '073', 'img' => null],
        ];

        foreach ($banks as $bank) {
            $bankId = DB::table('banks')->insertGetId([
                'code' => $bank['code'],
                'img' => $bank['img'],
                'status' => 'active',
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Insert localized names
            foreach (['fa', 'en'] as $locale) {
                DB::table('bank_options')->insert([
                    'bank_id' => $bankId,
                    'key' => 'name',
                    'value' => $bank[$locale],
                    'locale' => $locale,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
