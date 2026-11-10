<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Catalog\Database\Seeders\CatalogSeeder;
use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Toko',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ],
        );

        $customerUser = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Budi Pelanggan',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CUSTOMER,
            ],
        );

        $customer = Customer::firstOrCreate(
            ['user_id' => $customerUser->id],
            ['full_name' => $customerUser->name, 'phone' => '081234567890'],
        );

        Address::firstOrCreate(
            ['customer_id' => $customer->id, 'label' => 'Rumah'],
            [
                'recipient_name' => $customer->full_name,
                'phone' => '081234567890',
                'line1' => 'Jl. Padjajaran No. 12',
                'line2' => 'RT 05 RW 02',
                'city' => 'Sleman',
                'province' => 'DIY',
                'postal_code' => '55283',
                'is_default' => true,
            ],
        );

        $this->call(CatalogSeeder::class);

        $this->command?->info('Akun demo: admin@example.com / customer@example.com, password: password');
    }
}
