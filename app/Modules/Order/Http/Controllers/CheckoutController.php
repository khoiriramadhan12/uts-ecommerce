<?php

namespace App\Modules\Order\Http\Controllers;

use App\Modules\Customer\Contracts\CustomerProfileContract;
use App\Modules\Order\Http\Requests\CheckoutRequest;
use App\Modules\Order\Services\CartService;
use App\Modules\Order\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController
{
    public function __construct(
        private readonly CustomerProfileContract $customers,
        private readonly CartService $carts,
        private readonly CheckoutService $checkout,
    ) {}

    public function create(Request $request): View
    {
        $customer = $this->customers->findByUserId($request->user()->id);
        $cart = $this->carts->activeCartFor($customer->id);

        return view('order.checkout', [
            'cart' => $cart,
            'totals' => $this->carts->totals($cart),
            'addresses' => $this->customers->addressesFor($customer->id),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $order = $this->checkout->checkout(
            $request->user()->id,
            $request->integer('address_id'),
        );

        return redirect()->route('orders.show', $order->code)
            ->with('success', 'Pesanan '.$order->code.' berhasil dibuat.');
    }
}
