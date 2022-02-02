<?php

namespace App\Domain\Users\Actions\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseInterface;

/**
 * Class LogoutResponse
 * @package App\Domain\Users\Actions\Auth
 */
class LogoutResponse implements LogoutResponseInterface
{
    /**
     * @param Request $request
     *
     * @return JsonResponse|RedirectResponse
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse('', Response::HTTP_NO_CONTENT)
            : inertia()->forceRedirect(
                route('login')
            );
    }
}
