<?php

namespace App\Infrastructure\Providers;

use App\Application\Admin\InertiaJs\DataBindings;
use App\Infrastructure\Support\Contracts\InertiaDataSharable;
use App\Infrastructure\Support\Exceptions\InvalidInertiaDataSharableClassException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

/**
 * Class InertiaServiceProvider
 * @package App\Infrastructure\Providers
 */
class InertiaServiceProvider extends ServiceProvider
{
    use DataBindings;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Inertia::macro(
            'forceRedirect',
            function (string $url) {
                return response('', Response::HTTP_CONFLICT)->header('x-inertia-location', $url);
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws BindingResolutionException
     * @throws InvalidInertiaDataSharableClassException
     */
    public function boot()
    {
        collect($this->dataSharableClasses)->each(
            function (string $dataClass) {
                $class = $this->app->make($dataClass);

                if ( !($class instanceof InertiaDataSharable) ) {
                    throw new InvalidInertiaDataSharableClassException();
                }

                /** @phpstan-ignore-next-line */
                inertia()->share($class->key(), $class->data());
            }
        );
    }
}
