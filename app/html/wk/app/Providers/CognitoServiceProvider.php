<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Services\CognitoService;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

class CognitoServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('CognitoService', function (Application $app) {
            return new CognitoService(
                new CognitoIdentityProviderClient([
                    'region' => config('mywork.aws.default_region'),
                    'version' => config('mywork.aws.cognito_version'),
                ]),
                config('mywork.aws.cognito_app_client_id'),
                config('mywork.aws.cognito_user_pool_id')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
