<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Traitement\ruleTrait;

class ruleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ruleTrait::class);
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
