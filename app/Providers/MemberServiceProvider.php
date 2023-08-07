<?php

namespace App\Providers;


use App\Facades\Context\ApiResponse;
use App\Repositories\Members\Contracts\MemberRepositoryInterface;
use App\Repositories\Members\MemberRepository;
use Illuminate\Support\ServiceProvider;

class MemberServiceProvider extends ServiceProvider
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
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
    }
}
