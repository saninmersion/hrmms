<?php

namespace App\Infrastructure\Support\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class MobileNumber
 * @package App\Infrastructure\Support\Rules
 */
class MobileNumber implements Rule
{
    /**
     * @return string
     */
    public function message()
    {
        return trans('validation.mobile_number');
    }

    /**
     * @param string $attribute
     * @param int    $value
     *
     * @return bool|void
     * @SuppressWarnings("unused")
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function passes($attribute, $value)
    {
        $patterns = '/^9[87]{1}[0-9]{8}$/';

        return preg_match($patterns, (string) $value) > 0;
    }
}
