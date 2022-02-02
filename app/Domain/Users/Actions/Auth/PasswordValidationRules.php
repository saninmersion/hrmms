<?php

namespace App\Domain\Users\Actions\Auth;

use Laravel\Fortify\Rules\Password;

/**
 * Trait PasswordValidationRules
 * @package App\Domain\Users\Actions\Auth
 */
trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return ['required', 'string', new Password(), 'confirmed'];
    }
}
