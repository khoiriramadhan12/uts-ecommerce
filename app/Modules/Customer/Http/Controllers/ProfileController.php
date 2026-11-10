<?php

namespace App\Modules\Customer\Http\Controllers;

use App\Modules\Customer\Contracts\CustomerProfileContract;
use App\Modules\Customer\Http\Requests\UpdateProfileRequest;
use App\Modules\Customer\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController
{
    public function __construct(private readonly CustomerProfileContract $customers) {}

    public function edit(Request $request): View
    {
        return view('account.profile', [
            'customer' => $this->customers->findByUserId($request->user()->id),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Customer::where('user_id', $request->user()->id)->update([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'] ?? null,
        ]);

        $request->user()->update(['name' => $data['full_name']]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
