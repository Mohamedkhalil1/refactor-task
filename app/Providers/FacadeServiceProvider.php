<?php

namespace App\Providers;


use App\Facades\Context\ApiResponse;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->bind('api_response', function () {
            return new ApiResponse();
        });

    }
}
