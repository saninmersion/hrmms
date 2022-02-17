<?php

namespace App\Infrastructure\Constants;

use Illuminate\Support\Str;

/**
 * Class ApplicationConstants
 *
 * @package App\Infrastructure\Constants
 */
final class ApplicationConstants
{
    public const GENDER_MALE   = 'male';
    public const GENDER_FEMALE = 'female';
    public const GENDER_OTHER  = 'other';

    public const GRADING_SYSTEM_GRADE      = 'grade';
    public const GRADING_SYSTEM_PERCENTAGE = 'percentage';

    public const MAJOR_SUBJECT_AGRICULTURE    = 'agriculture';
    public const MAJOR_SUBJECT_ANIMAL_SCIENCE = 'animal_science';
    public const MAJOR_SUBJECT_STATISTICS     = 'statistics';
    public const MAJOR_SUBJECT_OTHERS         = 'others';

    public const DISABILITY_DISABLED     = 'disabled';
    public const DISABILITY_NOT_DISABLED = 'not_disabled';

    public const MONITORING_FORM_OVERALL    = 'overall_monitoring';
    public const MONITORING_FORM_SUPERVISOR = 'supervisor_monitoring';
    public const MONITORING_FORM_ENUMERATOR = 'enumerator_monitoring';

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function genders()
    : array
    {
        $all = self::all();

        return collect($all)->filter(
            // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'GENDER_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function gradingSystems()
    : array
    {
        $all = self::all();

        return collect($all)->filter(
            // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'GRADING_SYSTEM_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function majorSubjects()
    : array
    {
        $all = self::all();

        return collect($all)->filter(
            // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'MAJOR_SUBJECT_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function disabilities()
    : array
    {
        $all = self::all();

        return collect($all)->filter(
            // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'DISABILITY_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function monitoringForms()
    : array
    {
        $all = self::all();

        return collect($all)->filter(
            // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'MONITORING_FORM_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     */
    protected static function all()
    : array
    {
        try {
            $reflectionClass = new \ReflectionClass(__CLASS__);
        } catch (\ReflectionException $exception) {
            return [];
        }

        return $reflectionClass->getConstants();
    }
}
