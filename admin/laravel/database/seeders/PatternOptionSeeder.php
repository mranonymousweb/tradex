<?php

namespace Database\Seeders;

use App\Models\PatternOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatternOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userPatterns = config('patterns.user') ?? [];
        $adminPatterns = config('patterns.admin') ?? [];

        $patterns = array_merge($userPatterns, $adminPatterns);

        foreach ($patterns as $pattern) {
            $key = $pattern['key'];
            $description = $pattern['label'] ?? null;
            $defaultValues = $pattern['default_value'] ?? [];

            foreach ($defaultValues as $locale => $value) {
                PatternOption::updateOrCreate(
                    [
                        'key' => $key,
                        'type' => PatternOption::TYPE_EMAIL,
                        'local' => $locale,
                    ],
                    [
                        'value' => $value,
                        'description' => $description,
                    ]
                );
            }
        }
    }
}
