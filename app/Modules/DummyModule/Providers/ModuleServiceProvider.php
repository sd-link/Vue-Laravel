<?php

namespace App\Modules\DummyModule\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'dummy-module');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'dummy-module');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'dummy-module');

        $this->publishes([
            __DIR__.'/../Assets' => public_path('modules/dummy-module'),
        ], 'dummy-module');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
