<?php

namespace App\Infrastructure\Constants;

/**
 * Class ApplicationType
 * @package App\Infrastructure\Constants
 */
final class ApplicationType
{
    public const SUPERVISOR            = 'supervisor';
    public const ENUMERATOR            = 'enumerator';
    public const ENUMERATOR_SUPERVISOR = 'enumerator_supervisor';

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
