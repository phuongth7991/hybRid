<?php

namespace App\Providers;

use App\Helpers\SystemHelper;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['request']->server->set('HTTPS', env('FORCE_HTTPS', false));
        $this->app->singleton('system-helper', function () {
            return new SystemHelper();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('FORCE_HTTPS', false)) {
            \URL::forceScheme('https');
        }
        Paginator::useBootstrapFour();
    }
}
