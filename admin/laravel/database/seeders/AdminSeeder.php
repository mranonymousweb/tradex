<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Super',
            'family' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make(12345678),
            'mobile' => '09900000000',
        ];
        Admin::updateOrCreate(['id' => 1], $data);
    }
}
