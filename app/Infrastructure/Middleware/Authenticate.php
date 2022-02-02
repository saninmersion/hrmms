<?php

namespace App\Infrastructure\Middleware;

use App\Infrastructure\Constants\Guard;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Class Authenticate
 * @package App\Infrastructure\Middleware
 */
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @param array   $guards
     *
     * @return string|null
     */
    protected function multiGuardRedirectTo($request, array $guards)
    {
        if ( !$request->expectsJson() ) {
            return $guards[0] === Guard::APPLICANT ? route('front.home') : route('login');
        }

        return null;
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param Request $request
     * @param array   $guards
     *
     * @return void
     *
     * @throws AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->multiGuardRedirectTo($request, $guards)
        );
    }
}
