<?php

namespace KS\Ups;

use Illuminate\Support\ServiceProvider;

class UpsServiceProvider extends ServiceProvider
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
        $this->app->bind('ups', function(){
          return new Ups(config('app.ups'));
        });
    }
}
