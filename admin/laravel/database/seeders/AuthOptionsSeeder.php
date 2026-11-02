<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Option::whereIn('key',[Option::SATURN_API_KEY,Option::LIMIT_IP,Option::AUTHENTICATION_AGREEMENT,Option::FIRST_STEP_MANUAL,Option::MANUAL_HANDLE_WHEN_SATURN_PROBLEM,Option::CONFIRM_MOBILE_WITH_EMAIL,Option::EMAIL_VERIFICATION_REQUIRED])->delete();
        $options = [
            [
                'key' => Option::SATURN_API_KEY,
                'value' => encrypt('test'),
                'lang' => 'fa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => Option::LIMIT_IP,
                'value' => Option::INACTIVE,
                'lang' => 'fa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => Option::FIRST_STEP_MANUAL,
                'value' => Option::INACTIVE,
                'lang' => 'fa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => Option::MANUAL_HANDLE_WHEN_SATURN_PROBLEM,
                'value' => Option::ACTIVE,
                'lang' => 'fa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => Option::AUTHENTICATION_AGREEMENT,
                'value' => 'اینجانب …… به کد ملی ….. در تاریخ …….. با مطالعه و‌ قبول قوانین و قرارداد سایت والکس، تعهد می‌دهم حساب کاربری خود را در اختیار دیگران قرار ندهم در غیر این صورت، مسئول جبران خسارات برابر قوانین و قرارداد صرافی خواهم بود.',
                'lang' => 'fa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => Option::CONFIRM_MOBILE_WITH_EMAIL,
                'value' => 'inactive',
                'lang' => 'fa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => Option::EMAIL_VERIFICATION_REQUIRED,
                'value' => 'inactive',
                'lang' => 'fa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Option::insert($options);
    }
}
