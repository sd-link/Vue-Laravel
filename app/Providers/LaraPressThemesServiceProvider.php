<?php

namespace App\Providers;

/**
 * This service provider should be executed AFTER 'igaster\laravelTheme\themeServiceProvider'
 * (check config/app.php) in order to make some modifications to previously conficuration
 */


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Theme;

class LaraPressThemesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /*--------------------------------------------------------------------------
        | Replace FileViewFinder
        |--------------------------------------------------------------------------*/

        \App::singleton('view.finder', function($app)
        {
            return new \App\Classes\Themes\laraPressViewFinder(
                $app['files'],
                $app['config']['view.paths'],
                null
            );
        });

        /*--------------------------------------------------------------------------
        | Register helpers.php functions
        |--------------------------------------------------------------------------*/

        require_once __DIR__.'/../Classes/Themes/helpers.php';

    }
}
