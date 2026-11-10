<?php

namespace App\Modules\Customer\Providers;

use App\Modules\Customer\Contracts\CustomerProfileContract;
use App\Modules\Customer\Listeners\CreateCustomerProfile;
use App\Modules\Customer\Services\CustomerProfileService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CustomerProfileContract::class, CustomerProfileService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        Event::listen(Registered::class, CreateCustomerProfile::class);
    }
}
