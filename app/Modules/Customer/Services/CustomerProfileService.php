<?php

namespace App\Modules\Customer\Services;

use App\Modules\Customer\Contracts\CustomerProfileContract;
use App\Modules\Customer\DTO\AddressSnapshot;
use App\Modules\Customer\DTO\CustomerSnapshot;
use App\Modules\Customer\Exceptions\AddressNotOwnedException;
use App\Modules\Customer\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;

class CustomerProfileService implements CustomerProfileContract
{
    public function findByUserId(int $userId): CustomerSnapshot
    {
        $customer = Customer::where('user_id', $userId)->first();

        if ($customer === null) {
            throw new CustomerNotFoundException($userId);
        }

        return $this->toSnapshot($customer);
    }

    public function findById(int $customerId): CustomerSnapshot
    {
        $customer = Customer::find($customerId);

        if ($customer === null) {
            throw new CustomerNotFoundException($customerId);
        }

        return $this->toSnapshot($customer);
    }

    public function createForUser(int $userId, string $fullName): CustomerSnapshot
    {
        $customer = Customer::firstOrCreate(
            ['user_id' => $userId],
            ['full_name' => $fullName],
        );

        return $this->toSnapshot($customer);
    }

    public function assertAddressBelongsTo(int $customerId, int $addressId): AddressSnapshot
    {
        $address = Address::where('customer_id', $customerId)->find($addressId);

        if ($address === null) {
            throw new AddressNotOwnedException($addressId);
        }

        return $this->toAddressSnapshot($address);
    }

    public function addressesFor(int $customerId): array
    {
        return Address::where('customer_id', $customerId)
            ->orderByDesc('is_default')
            ->orderBy('label')
            ->get()
            ->map(fn (Address $address) => $this->toAddressSnapshot($address))
            ->all();
    }

    private function toSnapshot(Customer $customer): CustomerSnapshot
    {
        return new CustomerSnapshot(
            id: $customer->id,
            userId: $customer->user_id,
            fullName: $customer->full_name,
            phone: $customer->phone,
        );
    }

    private function toAddressSnapshot(Address $address): AddressSnapshot
    {
        return new AddressSnapshot(
            id: $address->id,
            customerId: $address->customer_id,
            label: $address->label,
            recipientName: $address->recipient_name,
            phone: $address->phone,
            line1: $address->line1,
            line2: $address->line2,
            city: $address->city,
            province: $address->province,
            postalCode: $address->postal_code,
            isDefault: $address->is_default,
        );
    }
}
