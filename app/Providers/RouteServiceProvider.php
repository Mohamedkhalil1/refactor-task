<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    public const API_ROUTES_PATH = 'routes/Api';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            $this->mapMembersRoutes();
            $this->mapVisitsRoutes();

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    public function mapMembersRoutes(): void
    {
        $files = glob(base_path(self::API_ROUTES_PATH.'/Member/*.php'));
        foreach ($files as $file) {
            Route::middleware('api')
                ->prefix('api/members')
                ->group($file);
        }
    }

    public function mapVisitsRoutes(): void
    {
        $files = glob(base_path(self::API_ROUTES_PATH.'/Visit/*.php'));
        foreach ($files as $file) {
            Route::middleware('api')
                ->prefix('api/visits')
                ->group($file);
        }
    }
}
