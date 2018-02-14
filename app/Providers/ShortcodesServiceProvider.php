<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shortcode;
use App\Shortcodes\BoldShortcode;
use App\Shortcodes\FormShortcode;

class ShortcodesServiceProvider extends ServiceProvider
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
        // Shortcode::register('b', BoldShortcode::class);
        Shortcode::register('form', FormShortcode::class);
    }
}
