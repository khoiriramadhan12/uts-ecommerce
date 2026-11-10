<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;
use App\Modules\Order\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_tidak_bisa_membuka_halaman_admin(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

        $this->actingAs($user)
            ->get(route('admin.catalog.products.index'))
            ->assertForbidden();
    }

    public function test_admin_bisa_membuka_halaman_admin(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($user)
            ->get(route('admin.catalog.products.index'))
            ->assertOk();
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get(route('cart.index'))->assertRedirect(route('login'));
    }

    public function test_customer_tidak_bisa_melihat_pesanan_orang_lain(): void
    {
        $user = User::factory()->create();
        Customer::factory()->create(['user_id' => $user->id]);

        $otherUser = User::factory()->create();
        $otherCustomer = Customer::factory()->create(['user_id' => $otherUser->id]);
        $otherAddress = Address::factory()->create(['customer_id' => $otherCustomer->id]);

        $order = Order::factory()->create([
            'customer_id' => $otherCustomer->id,
            'address_id' => $otherAddress->id,
            'code' => 'ORD-RAHASIA',
        ]);

        $this->actingAs($user)
            ->get(route('orders.show', $order->code))
            ->assertNotFound();
    }
}
