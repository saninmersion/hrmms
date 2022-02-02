<?php

namespace App\Infrastructure\Providers;

use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

/**
 * Class HorizonServiceProvider
 * @package App\Infrastructure\Providers
 */
class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        if ( config('config.log_horizon') ) {
            Horizon::routeSlackNotificationsTo(config('config.slack_webhook_url'), config('config.slack_channel'));
        }

        Horizon::night();
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define(
            'viewHorizon',
            function ($user) {
                /** @var User $user */
                return in_array($user->username ?? '', ['superadmin']);
            }
        );
    }
}
