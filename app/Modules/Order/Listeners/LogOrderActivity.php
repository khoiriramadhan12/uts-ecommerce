<?php

namespace App\Modules\Order\Listeners;

use App\Modules\Order\Events\OrderCancelled;
use App\Modules\Order\Events\OrderPaid;
use App\Modules\Order\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class LogOrderActivity
{
    public function onOrderPlaced(OrderPlaced $event): void
    {
        Log::info('order.placed', ['order' => $event->orderCode, 'customer_id' => $event->customerId]);
    }

    public function onOrderPaid(OrderPaid $event): void
    {
        Log::info('order.paid', ['order' => $event->orderCode, 'customer_id' => $event->customerId]);
    }

    public function onOrderCancelled(OrderCancelled $event): void
    {
        Log::info('order.cancelled', ['order' => $event->orderCode, 'customer_id' => $event->customerId]);
    }
}
