<?php

use App\Modules\Order\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Modules\Order\Http\Controllers\CartController;
use App\Modules\Order\Http\Controllers\CheckoutController;
use App\Modules\Order\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:sanctum', 'role:customer'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{code}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{code}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{code}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

Route::middleware(['web', 'auth:sanctum', 'role:admin'])
    ->prefix('admin/orders')
    ->name('admin.orders.')
    ->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order:code}', [AdminOrderController::class, 'show'])->name('show');
        Route::post('/{order}/complete', [AdminOrderController::class, 'complete'])->name('complete');
    });
