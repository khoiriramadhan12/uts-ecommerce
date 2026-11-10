<?php

namespace App\Modules\Order\Database\Factories;

use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;
use App\Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'code' => 'ORD-'.Str::upper(Str::random(8)),
            'customer_id' => Customer::factory(),
            'address_id' => Address::factory(),
            'status' => Order::STATUS_PENDING,
            'subtotal' => 100_000,
            'shipping_cost' => Order::SHIPPING_FLAT,
            'total' => 120_000,
            'shipping_snapshot' => [
                'recipient_name' => fake()->name(),
                'city' => fake()->city(),
                'province' => 'DIY',
                'postal_code' => fake()->numerify('#####'),
                'full_address' => fake()->streetAddress(),
            ],
            'placed_at' => now(),
        ];
    }
}
