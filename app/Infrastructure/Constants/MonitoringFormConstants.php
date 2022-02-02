<?php


namespace App\Infrastructure\Constants;


use Illuminate\Support\Str;

final class MonitoringFormConstants
{
    // बताए / बताएनन्
    public const FORM_OVERALL    = "overall_monitoring";
    public const FORM_SUPERVISOR = "supervisor_monitoring";
    public const FORM_ENUMERATOR = "enumerator_monitoring";

    // cha/chaina
    public const OPTIONS_YN_YES = "yes";
    public const OPTIONS_YN_NO  = "no";

    // बताए / बताएनन्
    public const OPTIONS_EXPLAINED_YES = "explained";
    public const OPTIONS_EXPLAINED_NO  = "not_explained";

    //गराए / गराएनन्
    public const OPTIONS_DONE_YES = "done";
    public const OPTIONS_DONE_NO  = "not_done";

    //गरे / गरेनन्
    public const OPTIONS_DID_YES = "did";
    public const OPTIONS_DID_NO  = "did_not";

    // रह्यो /  रहेन
    public const OPTIONS_REMAINED_YES = 'remained';
    public const OPTIONS_REMAINED_NO  = 'not_remained';

    // मिलेको / नमिलेको
    public const OPTIONS_ACCURATE_YES = 'accurate';
    public const OPTIONS_ACCURATE_NO  = 'inaccurate';

    // थिए / थिएनन्
    public const OPTIONS_WERE_YES = 'were';
    public const OPTIONS_WERE_NO  = 'were_not';

    // लेखेको/नलेखेकोे/आंशिक रुपमा लेखेको
    public const OPTIONS_WRITTEN_YES     = "written";
    public const OPTIONS_WRITTEN_NO      = "not_written";
    public const OPTIONS_WRITTEN_PARTIAL = "partially_written";

    // जानकारी भएको/जानकारी नभएको/आंशिक घर परिवारमा जानकारी भएको
    public const OPTIONS_INFORMED_YES     = 'informed';
    public const OPTIONS_INFORMED_NO      = 'not_informed';
    public const OPTIONS_INFORMED_PARTIAL = "partially_informed";

    //
    public const OPTIONS_MISSED_NO  = "not_missed";
    public const OPTIONS_MISSED_YES = 'missed';

    public const OPTIONS_HAS_YES = "has";
    public const OPTIONS_HAS_NO  = "doesnt_have";

    public const OPTIONS_HOME_OWNERSHIP_SELF        = 'self';
    public const OPTIONS_HOME_OWNERSHIP_RENT        = 'rent';
    public const OPTIONS_HOME_OWNERSHIP_INSTITUTION = 'institution';
    public const OPTIONS_HOME_OWNERSHIP_OTHER       = 'other';

    public const OPTIONS_ROOF_ZINC   = 'zinc_tin_sheet';
    public const OPTIONS_ROOF_CEMENT = 'cement';
    public const OPTIONS_ROOF_STRAW  = 'straw';
    public const OPTIONS_ROOF_TILE   = 'tile';
    public const OPTIONS_ROOF_STONE  = 'stone_slate';
    public const OPTIONS_ROOF_WOOD   = 'wood_plank';
    public const OPTIONS_ROOF_MUD    = 'mud';
    public const OPTIONS_ROOF_OTHER  = 'other';

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function explainedOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_EXPLAINED_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function remainedOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_REMAINED_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function accurateOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_ACCURATE_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function didOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_DID_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function doneOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_DONE_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function wereOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_WERE_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function writtenOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_WRITTEN_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function informedOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_INFORMED_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function missedOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_MISSED_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function hasOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_HAS_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function homeOwnerShipOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_HOME_OWNERSHIP_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function roofOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_ROOF_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function yesNoOptions(): array
    {
        $pagination = self::all();

        return collect($pagination)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'OPTIONS_YN_');
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
