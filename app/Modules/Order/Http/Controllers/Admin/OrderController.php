<?php

namespace App\Modules\Order\Http\Controllers\Admin;

use App\Modules\Customer\Contracts\CustomerProfileContract;
use App\Modules\Order\Exceptions\OrderNotPayableException;
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
        $orders = Order::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('order.admin.index', [
            'orders' => $orders,
            'customerNames' => $orders->getCollection()
                ->mapWithKeys(fn (Order $order) => [
                    $order->id => $this->customerName($order->customer_id),
                ]),
        ]);
    }

    public function show(Order $order): View
    {
        return view('order.admin.show', [
            'order' => $order->load('items'),
            'customerName' => $this->customerName($order->customer_id),
        ]);
    }

    public function complete(Order $order): RedirectResponse
    {
        try {
            $this->orders->complete($order);
        } catch (OrderNotPayableException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Pesanan ditandai selesai.');
    }

    private function customerName(int $customerId): string
    {
        return $this->customers->findById($customerId)->fullName;
    }
}
