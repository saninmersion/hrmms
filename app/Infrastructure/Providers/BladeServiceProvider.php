<?php

namespace App\Infrastructure\Providers;

use App\Application\Admin\View\Components\AuthLayout;
use App\Application\Front\View\Components\FrontLayout;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class BladeServiceProvider
 * @package App\Infrastructure\Providers
 */
class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::components(
            [
                'front-layout' => FrontLayout::class,
                'auth-layout'  => AuthLayout::class,
            ]
        );

        Blade::if(
            'env',
            function ($environment) {
                return app()->environment($environment);
            }
        );
    }
}
