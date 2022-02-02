<?php

namespace App\Infrastructure\Constants;

/**
 * Class Guard
 * @package App\Infrastructure\Constants
 */
final class Guard
{
    public const ADMIN     = 'admin';
    public const APPLICANT = 'applicant';
    public const API       = 'api';
    public const SANCTUM   = 'sanctum';

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
