<?php

namespace Database\Factories;

use App\Enum\UserCardStatus;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserCard>
 */
class UserCardFactory extends Factory
{
    protected $model = UserCard::class;

    public function definition()
    {
        $user = User::first();
        $bank = Bank::first();
        $currency = Currency::where('symbol', Currency::SYMBOL_IRT)->first();
        return [
            'user_id' => $user->id ?? 1157,
            'bank_id' => $bank->id,
            'currency_id' => $currency->id,
            'card_owner' => $user->getFullName(),
            'card_number' => $this->faker->numerify('603799##########'),
            'iban' => $this->faker->iban(),
        ];
    }
}
