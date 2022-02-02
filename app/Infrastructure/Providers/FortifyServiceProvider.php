<?php

namespace App\Infrastructure\Providers;

use App\Domain\Users\Actions\Auth\AttemptToAuthenticate;
use App\Domain\Users\Actions\Auth\CreateNewUser;
use App\Domain\Users\Actions\Auth\LoginResponse;
use App\Domain\Users\Actions\Auth\LogoutResponse;
use App\Domain\Users\Actions\Auth\RegisterResponse;
use App\Domain\Users\Actions\Auth\ResetUserPassword;
use App\Domain\Users\Actions\Auth\UpdateUserPassword;
use App\Domain\Users\Actions\Profile\UpdateUserProfileInformation;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Actions\AttemptToAuthenticate as BaseAttemptToAuthenticate;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Fortify;

/**
 * Class FortifyServiceProvider
 * @package App\Infrastructure\Providers
 */
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseAttemptToAuthenticate::class, AttemptToAuthenticate::class);
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
        $this->app->bind(RegisterResponseContract::class, RegisterResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(
            function (Request $request) {
                /** @var User|null $user */
                $user = User::where('username', $request->username)->first();

                if ( $user && $user->status !== UserStatus::STATUS_INACTIVE && Hash::check($request->password, $user->password) ) {
                    return $user;
                }
            }
        );
    }
}
