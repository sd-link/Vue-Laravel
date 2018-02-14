<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            'includes.sidebar', 'App\Http\ViewComposers\SidebarComposer'
        );

        // View::composer(
        //     'blog.index', 'App\Http\ViewComposers\SidebarComposer',
        //     'blog.show', 'App\Http\ViewComposers\SidebarComposer'
        // );


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
