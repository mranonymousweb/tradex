<?php

namespace Database\Seeders;

use App\Models\MarketingCode;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenerateMarketingCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::all();
        $code = strtoupper(Str::random(8));
        foreach ($user as $u) {

            MarketingCode::insert([
                'user_id' => $u->id,
                'code' => $code,
                'parent_percentage' => 100,
                'user_percentage' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
