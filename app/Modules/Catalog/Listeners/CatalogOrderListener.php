<?php

namespace App\Modules\Catalog\Listeners;

use App\Modules\Catalog\Models\Product;
use App\Modules\Order\Events\OrderCancelled;
use App\Modules\Order\Events\OrderPlaced;

/**
 * Efek samping lintas modul yang tidak boleh menggagalkan checkout.
 * Stok sudah dipotong lewat contract; listener hanya mengurus statistik penjualan.
 */
class CatalogOrderListener
{
    public function onOrderPlaced(OrderPlaced $event): void
    {
        foreach ($event->items as $item) {
            Product::whereKey($item['product_id'])->increment('sold_count', (int) $item['qty']);
        }
    }

    public function onOrderCancelled(OrderCancelled $event): void
    {
        foreach ($event->items as $item) {
            Product::whereKey($item['product_id'])
                ->where('sold_count', '>=', (int) $item['qty'])
                ->decrement('sold_count', (int) $item['qty']);
        }
    }
}
