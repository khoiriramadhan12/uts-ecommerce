<?php

namespace App\Modules\Order\Http\Controllers;

use App\Modules\Customer\Contracts\CustomerProfileContract;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController
{
    public function __construct(
        private readonly CustomerProfileContract $customers,
        private readonly OrderService $orders,
    ) {}

    public function index(Request $request): View
    {
        $customer = $this->customers->findByUserId($request->user()->id);

        return view('order.orders.index', [
            'orders' => Order::ownedBy($customer->id)->latest()->paginate(10),
        ]);
    }

    public function show(Request $request, string $code): View
    {
        return view('order.orders.show', [
            'order' => $this->findOwned($request, $code),
        ]);
    }

    public function pay(Request $request, string $code): RedirectResponse
    {
        $this->orders->pay($this->findOwned($request, $code));

        return back()->with('success', 'Pesanan ditandai sudah dibayar.');
    }

    public function cancel(Request $request, string $code): RedirectResponse
    {
        $this->orders->cancel($this->findOwned($request, $code));

        return back()->with('success', 'Pesanan dibatalkan dan stok dikembalikan.');
    }

    private function findOwned(Request $request, string $code): Order
    {
        $customer = $this->customers->findByUserId($request->user()->id);

        return Order::ownedBy($customer->id)->where('code', $code)->with('items')->firstOrFail();
    }
}
