<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
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
        // \View::composer('layouts.header', function($view){
        //     $view->with('quote', Inspiring::quote());
        // });   



        // Relation::morphMap([
        //     'App\Comment' => 'App\Comment',
        //     'App\Reply' => 'App\Reply'
        // ]);
        // Force SSL in production
		// if ($this->app->environment() == 'production') {
		//     URL::forceScheme('https');
		// }
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
