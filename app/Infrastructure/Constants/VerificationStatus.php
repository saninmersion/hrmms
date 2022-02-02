<?php

namespace App\Infrastructure\Constants;

/**
 * Class VerificationStatus
 * @package App\Infrastructure\Constants
 */
final class VerificationStatus
{
    public const OKAY                      = 'okay';
    public const CORRECTION_NEEDED         = 'correction_needed';
    public const RECOMMENDED_FOR_REJECTION = 'recommended_for_rejection';
    public const NOT_VERIFIED              = 'not_verified';

    /**
     * @return array
     */
    public static function all(): array
    {
        try {
            $reflectionClass = new \ReflectionClass(__CLASS__);
        } catch (\ReflectionException $exception) {
            return [];
        }

        return array_values($reflectionClass->getConstants());
    }
}
