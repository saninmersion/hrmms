<?php

namespace App\Infrastructure\Constants;

final class HiringStatus
{
    public const STATUS_HIRED    = "hired";
    public const STATUS_REJECTED = "rejected";

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
