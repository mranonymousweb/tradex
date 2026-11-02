<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name_en' => 'Human Resources', 'name_fa' => 'منابع انسانی'],
            ['name_en' => 'Sales', 'name_fa' => 'فروش'],
            ['name_en' => 'Marketing', 'name_fa' => 'بازاریابی'],
            ['name_en' => 'Finance', 'name_fa' => 'مالی'],
            ['name_en' => 'IT', 'name_fa' => 'فناوری اطلاعات'],
            ['name_en' => 'Customer Support', 'name_fa' => 'پشتیبانی مشتری'],
        ];
        DB::table('departments')->truncate();
        foreach ($departments as $departmentData) {

            $department = Department::create([
                'status' => 1,
            ]);

            $department->details()->create([
                'name' => $departmentData['name_en'],
                'local' => 'en',
            ]);

            $department->details()->create([
                'name' => $departmentData['name_fa'],
                'local' => 'fa',
            ]);
        }
    }
}
