<?php

namespace App\Providers;


use App\Facades\Context\ApiResponse;
use App\Repositories\Cashier\CashierRepository;
use App\Repositories\Cashier\Contracts\CashierRepositoryInterface;
use App\Repositories\Members\Contracts\MemberRepositoryInterface;
use App\Repositories\Members\MemberRepository;
use Illuminate\Support\ServiceProvider;

class CashierServiceProvider extends ServiceProvider
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
        $this->app->bind(CashierRepositoryInterface::class, CashierRepository::class);
    }
}
