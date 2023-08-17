<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class user_role extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('test',function() {
            return new \App\Traits\User_roles;
         });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
