<?php

namespace App\Modules\Order\Services;

use App\Modules\Catalog\Contracts\ProductCatalogContract;
use App\Modules\Order\Events\OrderCancelled;
use App\Modules\Order\Events\OrderPaid;
use App\Modules\Order\Exceptions\OrderNotPayableException;
use App\Modules\Order\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private readonly ProductCatalogContract $catalog) {}

    public function pay(Order $order): void
    {
        $this->ensurePending($order);

        $order->update(['status' => Order::STATUS_PAID]);

        OrderPaid::dispatch($order->id, $order->code, $order->customer_id);
    }

    /**
     * Stok dikembalikan lewat contract di dalam transaksi, lalu event
     * dipancarkan setelah commit untuk statistik penjualan.
     */
    public function cancel(Order $order): void
    {
        $this->ensurePending($order);

        $items = $order->items()->get();

        DB::transaction(function () use ($order, $items): void {
            foreach ($items as $item) {
                $this->catalog->releaseStock($item->product_id, $item->qty);
            }

            $order->update(['status' => Order::STATUS_CANCELLED]);
        });

        OrderCancelled::dispatch(
            $order->id,
            $order->code,
            $order->customer_id,
            $items->map(fn ($item): array => [
                'product_id' => $item->product_id,
                'qty' => $item->qty,
            ])->all(),
        );
    }

    public function complete(Order $order): void
    {
        if ($order->status !== Order::STATUS_PAID) {
            throw new OrderNotPayableException($order->status);
        }

        $order->update(['status' => Order::STATUS_COMPLETED]);
    }

    private function ensurePending(Order $order): void
    {
        if (! $order->isPending()) {
            throw new OrderNotPayableException($order->status);
        }
    }
}
