<?php

namespace App\Modules\Customer\Contracts;

use App\Modules\Customer\DTO\AddressSnapshot;
use App\Modules\Customer\DTO\CustomerSnapshot;
use App\Modules\Customer\Exceptions\AddressNotOwnedException;
use App\Modules\Customer\Exceptions\CustomerNotFoundException;

/**
 * Pintu masuk modul lain ke data pelanggan dan alamat.
 */
interface CustomerProfileContract
{
    /**
     * @throws CustomerNotFoundException
     */
    public function findByUserId(int $userId): CustomerSnapshot;

    /**
     * @throws CustomerNotFoundException
     */
    public function findById(int $customerId): CustomerSnapshot;

    public function createForUser(int $userId, string $fullName): CustomerSnapshot;

    /**
     * @throws AddressNotOwnedException
     */
    public function assertAddressBelongsTo(int $customerId, int $addressId): AddressSnapshot;

    /**
     * @return array<int, AddressSnapshot>
     */
    public function addressesFor(int $customerId): array;
}
