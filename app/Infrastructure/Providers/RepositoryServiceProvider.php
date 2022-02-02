<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Infrastructure\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        collect(config('repository-bindings'))->each(
            function (string $implementation, string $interface) {
                $this->app->bind($interface, $implementation);
            }
        );
    }
}
