<?php

namespace App\Modules\Order\Database\Factories;

use App\Modules\Customer\Models\Customer;
use App\Modules\Order\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'status' => Cart::STATUS_ACTIVE,
        ];
    }
}
