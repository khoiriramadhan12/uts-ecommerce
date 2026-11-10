<?php

namespace App\Modules\Order\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Dipancarkan setelah transaksi checkout commit.
 */
class OrderPlaced
{
    use Dispatchable, SerializesModels;

    /**
     * @param  array<int, array{product_id: int, qty: int}>  $items
     */
    public function __construct(
        public readonly int $orderId,
        public readonly string $orderCode,
        public readonly int $customerId,
        public readonly array $items,
    ) {}
}
