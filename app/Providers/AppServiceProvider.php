<?php

namespace App\Providers;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \View::composer('layouts.header', function($view){
            $view->with('quote', Inspiring::quote());
        });   

        // Force SSL in production
		if ($this->app->environment() == 'production') {
		    URL::forceScheme('https');
		}
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
