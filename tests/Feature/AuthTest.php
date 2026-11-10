<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Customer\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registrasi_membuat_user_dan_profil_pelanggan(): void
    {
        $this->post(route('register'), [
            'name' => 'Siti Pembeli',
            'email' => 'siti@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('home'));

        $user = User::where('email', 'siti@example.com')->sole();

        $this->assertSame(User::ROLE_CUSTOMER, $user->role);
        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'full_name' => 'Siti Pembeli',
        ]);
        $this->assertSame(1, Customer::count());
    }

    public function test_login_berhasil_dengan_kredensial_benar(): void
    {
        $user = User::factory()->create([
            'email' => 'budi@example.com',
            'password' => 'password',
            'role' => User::ROLE_CUSTOMER,
        ]);

        $this->post(route('login'), [
            'email' => 'budi@example.com',
            'password' => 'password',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        User::factory()->create(['email' => 'budi@example.com']);

        $this->post(route('login'), [
            'email' => 'budi@example.com',
            'password' => 'salah',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
