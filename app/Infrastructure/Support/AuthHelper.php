<?php

namespace App\Infrastructure\Support;

use App\Application\Front\Support\Exceptions\FormNonEditableException;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\Guard;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class AuthHelper
 * @package App\Infrastructure\Support
 */
class AuthHelper
{
    /**
     * @param string $guard
     *
     * @return Authenticatable|User|Applicant|null
     */
    public static function currentUser(string $guard = Guard::ADMIN)
    {
        return auth($guard)->user();
    }

    /**
     * @return void
     * @throws FormNonEditableException
     */
    public static function guardEditableForm(): void
    {
        /** @var Applicant $applicant */
        $applicant = self::currentUser(Guard::APPLICANT);

        $isEditable = $applicant->is_editable ?? true;

        if ( !$isEditable ) {
            throw new FormNonEditableException();
        }
    }
}
