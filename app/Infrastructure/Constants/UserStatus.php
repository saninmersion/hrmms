<?php

namespace App\Infrastructure\Constants;

final class UserStatus
{
    public const STATUS_ACTIVE   = "active";
    public const STATUS_INACTIVE = "inactive";

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
