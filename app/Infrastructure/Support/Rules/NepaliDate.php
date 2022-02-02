<?php

namespace App\Infrastructure\Support\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class NepaliDate
 * @package App\Infrastructure\Support\Rules
 */
class NepaliDate implements Rule
{
    /**
     * @return string
     */
    public function message()
    {
        return trans('validation.nepali_date');
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return bool|void
     * @SuppressWarnings("unused")
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function passes($attribute, $value)
    {
        $patterns = '/^(19|20)\d{2}[-\/](0[1-9]|1[012]|[1-9]{1})[-\/](0[1-9]|[12]\d{1}|3[012]|[1-9])$/';

        return preg_match($patterns, $value) > 0;
    }
}
