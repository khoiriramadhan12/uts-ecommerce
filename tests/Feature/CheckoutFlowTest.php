<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;
use App\Modules\Order\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_memotong_stok_dan_membuat_pesanan(): void
    {
        [$user, $customer, $address] = $this->customerWithAddress();
        $product = Product::factory()->create(['price' => 100_000, 'stock' => 10]);

        $this->actingAs($user)
            ->post(route('cart.store'), ['product_id' => $product->id, 'qty' => 2])
            ->assertRedirect();

        $this->assertSame(10, $product->fresh()->stock);

        $response = $this->actingAs($user)
            ->post(route('checkout.store'), ['address_id' => $address->id]);

        $order = Order::sole();

        $response->assertRedirect(route('orders.show', $order->code));
        $this->assertSame(8, $product->fresh()->stock);
        $this->assertSame(2, $product->fresh()->sold_count);
        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertSame(1, $order->items()->count());
        $this->assertSame(2, $order->items()->first()->qty);
        $this->assertSame('100000.00', $order->items()->first()->unit_price);
        $this->assertSame(220_000.0, (float) $order->total);
    }

    public function test_checkout_gagal_saat_stok_kurang_dan_transaksi_rollback(): void
    {
        [$user, $customer, $address] = $this->customerWithAddress();
        $product = Product::factory()->create(['price' => 50_000, 'stock' => 5]);

        $this->actingAs($user)
            ->post(route('cart.store'), ['product_id' => $product->id, 'qty' => 2]);

        $product->update(['stock' => 1]);

        $response = $this->actingAs($user)
            ->post(route('checkout.store'), ['address_id' => $address->id]);

        $response->assertSessionHas('error');
        $this->assertSame(0, Order::count());
        $this->assertSame(1, $product->fresh()->stock);
        $this->assertSame(1, $customer->id);
    }

    public function test_pembatalan_pesanan_mengembalikan_stok(): void
    {
        [$user, $customer, $address] = $this->customerWithAddress();
        $product = Product::factory()->create(['price' => 75_000, 'stock' => 4]);

        $this->actingAs($user)->post(route('cart.store'), ['product_id' => $product->id, 'qty' => 3]);
        $this->actingAs($user)->post(route('checkout.store'), ['address_id' => $address->id]);

        $order = Order::sole();
        $this->assertSame(1, $product->fresh()->stock);

        $this->actingAs($user)
            ->post(route('orders.cancel', $order->code))
            ->assertRedirect();

        $this->assertSame(Order::STATUS_CANCELLED, $order->fresh()->status);
        $this->assertSame(4, $product->fresh()->stock);
        $this->assertSame(0, $product->fresh()->sold_count);
    }

    public function test_checkout_menolak_alamat_milik_customer_lain(): void
    {
        [$user, $customer, $address] = $this->customerWithAddress();
        $otherUser = User::factory()->create();

        $otherCustomer = Customer::factory()->create(['user_id' => $otherUser->id]);
        $otherAddress = Address::factory()->create(['customer_id' => $otherCustomer->id]);

        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user)->post(route('cart.store'), ['product_id' => $product->id, 'qty' => 1]);

        $this->actingAs($user)
            ->post(route('checkout.store'), ['address_id' => $otherAddress->id])
            ->assertSessionHas('error');

        $this->assertSame(0, Order::count());
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_form_request_menolak_jumlah_nol(): void
    {
        [$user] = $this->customerWithAddress();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user)
            ->post(route('cart.store'), ['product_id' => $product->id, 'qty' => 0])
            ->assertSessionHasErrors('qty');
    }

    /**
     * @return array{0: User, 1: Customer, 2: Address}
     */
    private function customerWithAddress(): array
    {
        $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $address = Address::factory()->create(['customer_id' => $customer->id]);

        return [$user, $customer, $address];
    }
}
