<?php

namespace App\Modules\Customer\Database\Factories;

use App\Models\User;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => User::ROLE_CUSTOMER]),
            'full_name' => fake()->name(),
            'phone' => fake()->numerify('08##########'),
        ];
    }
}
