<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\Tag;
use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_semua_halaman_admin_dapat_dirender(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        Tag::factory()->create();

        $urls = [
            route('admin.catalog.products.index'),
            route('admin.catalog.products.create'),
            route('admin.catalog.products.edit', $product),
            route('admin.catalog.categories.index'),
            route('admin.catalog.categories.create'),
            route('admin.catalog.categories.edit', $category),
            route('admin.catalog.tags.index'),
            route('admin.orders.index'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_semua_halaman_customer_dapat_dirender(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $address = Address::factory()->create(['customer_id' => $customer->id]);

        $urls = [
            route('catalog.index'),
            route('catalog.show', Product::factory()->create()->slug),
            route('cart.index'),
            route('checkout.create'),
            route('orders.index'),
            route('account.profile.edit'),
            route('account.addresses.index'),
            route('account.addresses.create'),
            route('account.addresses.edit', $address->id),
        ];

        foreach ($urls as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }
}
