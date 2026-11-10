<?php

use App\Modules\Customer\Http\Controllers\AddressController;
use App\Modules\Customer\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:sanctum', 'role:customer'])
    ->prefix('account')
    ->name('account.')
    ->group(function () {
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('addresses', [AddressController::class, 'index'])->name('addresses.index');
        Route::get('addresses/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('addresses', [AddressController::class, 'store'])->name('addresses.store');
        Route::get('addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
        Route::put('addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::delete('addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });
