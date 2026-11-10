<?php

namespace App\Modules\Customer\Database\Factories;

use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'label' => fake()->randomElement(['Rumah', 'Kantor', 'Kos']),
            'recipient_name' => fake()->name(),
            'phone' => fake()->numerify('08##########'),
            'line1' => fake()->streetAddress(),
            'line2' => fake()->optional()->secondaryAddress(),
            'city' => fake()->city(),
            'province' => fake()->randomElement(['DIY', 'Jawa Tengah', 'Jawa Timur', 'DKI Jakarta']),
            'postal_code' => fake()->numerify('#####'),
            'is_default' => false,
        ];
    }
}
