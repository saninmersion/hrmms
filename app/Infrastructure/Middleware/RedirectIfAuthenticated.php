<?php

namespace App\Infrastructure\Middleware;

use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Providers\RouteServiceProvider;
use App\Infrastructure\Support\AuthHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RedirectIfAuthenticated
 * @package App\Infrastructure\Middleware
 */
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request     $request
     * @param Closure     $next
     * @param string|null ...$guards
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [Guard::ADMIN] : $guards;

        foreach ($guards as $guard) {
            $isLoggedIn = Auth::guard($guard)->check();
            if ( !$isLoggedIn ) {
                continue;
            }

            if ( $guard === Guard::ADMIN ) {
                /** @var User $currentUser */
                $currentUser = AuthHelper::currentUser();
                $route       = ($currentUser->role === AuthRoles::VERIFIERS) ? route('admin.assigned-applications.index') : RouteServiceProvider::dashboard();

                return redirect($route);
            }

            if ( $guard === Guard::APPLICANT ) {
                return redirect()->route('front.application.form');
            }
        }

        return $next($request);
    }
}
