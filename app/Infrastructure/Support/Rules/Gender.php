<?php

namespace App\Infrastructure\Support\Rules;

use App\Infrastructure\Constants\General;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class Gender
 * @package App\Infrastructure\Support\Rules
 */
class Gender implements Rule
{
    /**
     * @return string
     */
    public function message()
    {
        return trans('validation.gender');
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
        $genders  = implode('|', General::genderOptions());
        $patterns = "/^({$genders})\$/";

        return preg_match($patterns, $value) > 0;
    }
}
