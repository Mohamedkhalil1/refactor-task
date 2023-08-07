<?php

namespace App\Providers;


use App\Facades\Context\ApiResponse;
use App\Repositories\Members\Contracts\MemberRepositoryInterface;
use App\Repositories\Members\MemberRepository;
use App\Repositories\Visits\Contracts\CashierRepositoryInterface;
use App\Repositories\Visits\Contracts\VisitRepositoryInterface;
use App\Repositories\Visits\VisitRepository;
use Illuminate\Support\ServiceProvider;

class VisitServiceProvider extends ServiceProvider
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
        $this->app->bind(VisitRepositoryInterface::class, VisitRepository::class);
    }
}
