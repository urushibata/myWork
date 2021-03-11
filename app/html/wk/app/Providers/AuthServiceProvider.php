<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Auth\CognitoUserProvider;
use App\Guard\CognitoGuard;
use App\Guard\MyWorkGuard;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Auth::provider('cognito_provider', function($app, array $config) {
            return new CognitoUserProvider();
        });

/*
        $this->app['auth']->extend('cognito', function (Application $app, $name, array $config) {
            Log::debug('boot auth extend cognito.');

            $guard = new CognitoGuard(
                $name,
                $app['auth']->createUserProvider($config['provider']),
                $app['session.store'],
                $app['request']
            );

            $guard->setCookieJar($this->app['cookie']);
            $guard->setDispatcher($this->app['events']);
            $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));

            return $guard;
        });
*/

        $this->app['auth']->extend('mywork', function (Application $app, $name, array $config) {
            $guard = new MyWorkGuard(
                $name,
                $app['auth']->createUserProvider($config['provider']),
                $app['session.store'],
                $app['request']
            );

            $guard->setCookieJar($this->app['cookie']);
            $guard->setDispatcher($this->app['events']);
            $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));

            return $guard;
        });
    }
}
