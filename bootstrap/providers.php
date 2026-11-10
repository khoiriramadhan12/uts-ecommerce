<?php

use App\Modules\Catalog\Providers\CatalogServiceProvider;
use App\Modules\Customer\Providers\CustomerServiceProvider;
use App\Modules\Order\Providers\OrderServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    CatalogServiceProvider::class,
    CustomerServiceProvider::class,
    OrderServiceProvider::class,
];
