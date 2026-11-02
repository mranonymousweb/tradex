<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'family' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => $this->faker->unique()->numerify('09#########'),
            'tel' => $this->faker->unique()->numerify('021#######'),
            'avatar' => null,
            'status' => User::STATUS_ACTIVE,
            'password' => bcrypt('password'), // پسورد دیفالت
            'country_id' => User::IRAN_COUNTRY_ID,
            'address' => $this->faker->address(),
            'package_id'=>rand(1,3)
        ];
    }
}
