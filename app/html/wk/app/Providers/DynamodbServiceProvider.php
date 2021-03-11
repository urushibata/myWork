<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DynamodbService;
use Illuminate\Contracts\Support\DeferrableProvider;

class DynamodbServiceProvider extends ServiceProvider
// implements DeferrableProvider
{
    public $bindings = [
        'DynamodbService' => DynamodbService::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [DynamodbService::class];
    }
}
