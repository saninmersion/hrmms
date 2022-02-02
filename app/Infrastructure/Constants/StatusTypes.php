<?php

namespace App\Infrastructure\Constants;

/**
 * Class StatusTypes
 * @package App\Infrastructure\Constants
 */
final class StatusTypes
{
    public const APPLICATION_DRAFT     = 'draft';
    public const APPLICATION_SUBMITTED = 'submitted';

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
