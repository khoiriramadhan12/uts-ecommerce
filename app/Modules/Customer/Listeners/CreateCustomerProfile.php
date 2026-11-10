<?php

namespace App\Modules\Customer\Listeners;

use App\Modules\Customer\Contracts\CustomerProfileContract;
use Illuminate\Auth\Events\Registered;

/**
 * Registrasi user otomatis membuat profil pelanggan tanpa OrderModule
 * menyentuh tabel customers secara langsung.
 */
class CreateCustomerProfile
{
    public function __construct(private readonly CustomerProfileContract $customers) {}

    public function handle(Registered $event): void
    {
        $this->customers->createForUser($event->user->id, $event->user->name);
    }
}
