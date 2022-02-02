<?php

namespace App\Domain\Users\Actions\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\AttemptToAuthenticate as BaseAttemptToAuthenticate;

/**
 * Class AttemptToAuthenticate
 * @package App\Domain\Users\Actions\Auth
 */
class AttemptToAuthenticate extends BaseAttemptToAuthenticate
{
    /**
     * @param Request $request
     *
     * @throws ValidationException
     */
    protected function throwFailedAuthenticationException($request)
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages(
            [
                'error' => [trans('auth.failed')],
            ]
        );
    }
}
