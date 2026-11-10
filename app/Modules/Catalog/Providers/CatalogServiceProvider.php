<?php

namespace App\Modules\Catalog\Providers;

use App\Modules\Catalog\Contracts\ProductCatalogContract;
use App\Modules\Catalog\Listeners\CatalogOrderListener;
use App\Modules\Catalog\Services\ProductCatalogService;
use App\Modules\Order\Events\OrderCancelled;
use App\Modules\Order\Events\OrderPlaced;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductCatalogContract::class, ProductCatalogService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        Event::listen(OrderPlaced::class, [CatalogOrderListener::class, 'onOrderPlaced']);
        Event::listen(OrderCancelled::class, [CatalogOrderListener::class, 'onOrderCancelled']);
    }
}
