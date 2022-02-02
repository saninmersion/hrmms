<?php


namespace App\Domain\Users\Actions\Auth;

use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\UserStatus;
use App\Infrastructure\Support\AuthHelper;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;


class RegisterResponse implements RegisterResponseContract
{

    /**
     * @inheritDoc
     */
    public function toResponse($request)
    {
        /** @var User $currentUser */
        $currentUser = AuthHelper::currentUser();

        if( $currentUser->status === UserStatus::STATUS_INACTIVE ) {
            auth()->logout();
            return redirect()->route('login');
        }

        return $request->wantsJson() ? response()->json(['two_factor' => false]) : redirect()->intended('/');
    }
}
