<?php

namespace App\Infrastructure\Support\Rules;

use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Support\Helper;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class Age
 * @package App\Infrastructure\Support\Rules
 */
class Age implements Rule
{
    /**
     * @var string
     */
    protected string $gender;

    /**
     * Age constructor.
     *
     * @param string $gender
     */
    public function __construct(string $gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function message()
    {
        return trans('validation.age');
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return bool|void
     * @throws Exception
     * @SuppressWarnings("unused")
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function passes($attribute, $value)
    {
        /** @var Carbon $dob */
        $dob      = Carbon::createFromFormat('Y-m-d', $value);
        $age      = Helper::deadline()->diffInYears($dob);
        $ageLimit = config('config.age-limit');

        if ( !in_array($this->gender, ApplicationConstants::genders()) ) {
            return false;
        }

        return $age >= $ageLimit[$this->gender][0] && $age < $ageLimit[$this->gender][1];
    }
}
