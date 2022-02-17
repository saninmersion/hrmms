<?php

namespace App\Domain\Users\Actions\Auth;

use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginResponse
 *
 * @package App\Domain\Users\Actions\Auth
 */
class LoginResponse implements LoginResponseContract
{
    /**
     * @param Request $request
     *
     * @return JsonResponse|RedirectResponse|Response
     */
    public function toResponse($request)
    {
        /** @var User $currentUser */
        $currentUser = AuthHelper::currentUser();

        $roleRouteMappings = [
            AuthRoles::SUPER_ADMIN => config('fortify.home'),
            AuthRoles::ADMIN       => config('fortify.home'),
            AuthRoles::STAFFS      => config('fortify.home'),
            AuthRoles::VERIFIERS   => route('admin.assigned-applications.index'),
        ];

        $route = $roleRouteMappings[$currentUser->role] ?? config('fortify.home');

        return $request->wantsJson() ? response()->json(['two_factor' => false]) : redirect()->intended($route);
    }
}
