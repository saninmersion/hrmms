<?php

namespace App\Infrastructure\Providers;

use App\Infrastructure\Constants\Guard;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/**
 * Class RouteServiceProvider
 * @package App\Infrastructure\Providers
 */
class RouteServiceProvider extends ServiceProvider
{

    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @return string
     */
    public static function dashboard(): string
    {
        return "/";
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(
            function () {
                Route::prefix('api')->middleware('api')->group(base_path('routes/api.php'));

                if ( $this->app->environment('local') ) {
                    Route::prefix('dev')->middleware('web')->group(base_path('routes/dev.php'));
                }

                $adminGuard = Guard::ADMIN;
                Route::group(
                    [
                        'domain'     => config('config.domain.admin'),
                        'middleware' => ['admin', "auth:{$adminGuard}"],
                        'as'         => 'admin.',
                    ],
                    function () {
                        require base_path('routes/admin.php');
                    }
                );

                Route::domain(config('config.domain.front'))->middleware('front')->as('front.')->group(
                    base_path('routes/front.php')
                );

                Route::prefix('utils')->middleware('web')->group(base_path('routes/utils.php'));
            }
        );
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for(
            'api',
            function (Request $request) {
                return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
            }
        );
    }
}
