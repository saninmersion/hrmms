<?php

namespace App\Infrastructure\Constants;

use Illuminate\Support\Str;

/**
 * Class DailyReportConstants
 * @package App\Infrastructure\Constants
 */
final class DailyReportConstants
{
    public const DAILY_REPORT_CENSUS       = 'census';
    public const DAILY_REPORT_HOUSE_SURVEY = 'house_survey';

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function reportType(): array
    {
        $all = self::all();

        return collect($all)->filter(
            // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'DAILY_REPORT_');
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
