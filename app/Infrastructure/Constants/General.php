<?php

namespace App\Infrastructure\Constants;

use Illuminate\Support\Str;

/**
 * Class General
 * @package App\Infrastructure\Constants
 */
final class General
{
    public const PAGINATE_XXS = 5;
    public const PAGINATE_XS  = 10;
    public const PAGINATE_SM  = 15;
    public const PAGINATE_MD  = 25;
    public const PAGINATE_LG  = 50;
    public const PAGINATE_XL  = 75;
    public const PAGINATE_XXL = 100;

    public const GENDER_MALE   = 'male';
    public const GENDER_FEMALE = 'female';
    public const GENDER_OTHER  = 'other';

    public const DATE_FORMAT = 'j F, Y';

    public const LOCALE_SESSION_KEY = 'census-locale';

    public const PATH_APPLICATION = 'applications';
    public const PATH_EXPORTS     = 'exports';

    /**
     * @return int
     */
    public static function paginateDefault(): int
    {
        return self::PAGINATE_SM;
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function paginateOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'PAGINATE_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function genderOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'GENDER_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     */
    protected static function all(): array
    {
        try {
            $reflectionClass = new \ReflectionClass(__CLASS__);
        } catch (\ReflectionException $exception) {
            return [];
        }

        return $reflectionClass->getConstants();
    }
}
