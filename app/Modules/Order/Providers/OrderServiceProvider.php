<?php

namespace App\Modules\Order\Providers;

use App\Modules\Order\Events\OrderCancelled;
use App\Modules\Order\Events\OrderPaid;
use App\Modules\Order\Events\OrderPlaced;
use App\Modules\Order\Listeners\LogOrderActivity;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        Event::listen(OrderPlaced::class, [LogOrderActivity::class, 'onOrderPlaced']);
        Event::listen(OrderPaid::class, [LogOrderActivity::class, 'onOrderPaid']);
        Event::listen(OrderCancelled::class, [LogOrderActivity::class, 'onOrderCancelled']);
    }
}
