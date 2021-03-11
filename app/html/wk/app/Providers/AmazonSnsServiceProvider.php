<?php

namespace App\Providers;

use App\Services\AmazonSnsService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class AmazonSnsServiceProvider extends ServiceProvider // implements DeferrableProvider
{
    public $bindings = [
        'AmazonSnsService' => AmazonSnsService::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
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
        return [AmazonSnsService::class];
    }
}
