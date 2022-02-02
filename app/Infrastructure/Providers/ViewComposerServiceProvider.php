<?php

namespace App\Infrastructure\Providers;

use App\Infrastructure\Support\BikramSambat\BikramSambat;
use App\Infrastructure\Support\Translations\TranslationViewComposer;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

/**
 * Class ViewComposerServiceProvider
 * @package App\Infrastructure\Providers
 */
class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['layouts.*'], TranslationViewComposer::class);
        view()->composer(
            ['components.front.header'],
            function ($view) {
                /** @var Carbon $deadline */
                $deadline = BikramSambat::bsToAd(config('config.deadline'));
                $view->with(['deadline' => $deadline->format('Y/m/d')]);
            }
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
