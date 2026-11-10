<?php

namespace App\Modules\Customer\Http\Controllers;

use App\Modules\Customer\Contracts\CustomerProfileContract;
use App\Modules\Customer\Http\Requests\StoreAddressRequest;
use App\Modules\Customer\Http\Requests\UpdateAddressRequest;
use App\Modules\Customer\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AddressController
{
    public function __construct(private readonly CustomerProfileContract $customers) {}

    public function index(Request $request): View
    {
        $customer = $this->customers->findByUserId($request->user()->id);

        return view('account.addresses.index', [
            'addresses' => $this->customers->addressesFor($customer->id),
        ]);
    }

    public function create(): View
    {
        return view('account.addresses.create');
    }

    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $customer = $this->customers->findByUserId($request->user()->id);
        $data = $request->validated();

        DB::transaction(function () use ($customer, $data): void {
            if (! empty($data['is_default'])) {
                Address::where('customer_id', $customer->id)->update(['is_default' => false]);
            }

            Address::create([
                'customer_id' => $customer->id,
                'label' => $data['label'],
                'recipient_name' => $data['recipient_name'],
                'phone' => $data['phone'],
                'line1' => $data['line1'],
                'line2' => $data['line2'] ?? null,
                'city' => $data['city'],
                'province' => $data['province'],
                'postal_code' => $data['postal_code'],
                'is_default' => ! empty($data['is_default']),
            ]);
        });

        return redirect()->route('account.addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit(Request $request, int $address): View
    {
        return view('account.addresses.edit', [
            'address' => $this->ownedAddress($request, $address),
        ]);
    }

    public function update(UpdateAddressRequest $request, int $address): RedirectResponse
    {
        $model = $this->ownedAddress($request, $address);
        $data = $request->validated();

        DB::transaction(function () use ($model, $data): void {
            if (! empty($data['is_default'])) {
                Address::where('customer_id', $model->customer_id)->update(['is_default' => false]);
            }

            $model->update([
                'label' => $data['label'],
                'recipient_name' => $data['recipient_name'],
                'phone' => $data['phone'],
                'line1' => $data['line1'],
                'line2' => $data['line2'] ?? null,
                'city' => $data['city'],
                'province' => $data['province'],
                'postal_code' => $data['postal_code'],
                'is_default' => ! empty($data['is_default']),
            ]);
        });

        return redirect()->route('account.addresses.index')
            ->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(Request $request, int $address): RedirectResponse
    {
        $this->ownedAddress($request, $address)->delete();

        return redirect()->route('account.addresses.index')
            ->with('success', 'Alamat berhasil dihapus.');
    }

    private function ownedAddress(Request $request, int $addressId): Address
    {
        $customer = $this->customers->findByUserId($request->user()->id);

        return Address::where('customer_id', $customer->id)->findOrFail($addressId);
    }
}
