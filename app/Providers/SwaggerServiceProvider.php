<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenApi\Generator;

class SwaggerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the Swagger Generator
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Optionally publish the configuration
    }
}
