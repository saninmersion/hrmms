<?php

namespace App\Infrastructure\Constants;

use App\Infrastructure\Support\Helper;

/**
 * Class AuthRoles
 * @package App\Infrastructure\Constants
 */
class AuthRoles
{
    public const SUPER_ADMIN     = 'super_admin';
    public const ADMIN           = 'admin';
    public const STAFFS          = 'staffs';
    public const DISTRICT_STAFFS = 'district_staffs';
    public const VERIFIERS       = 'verifiers';
    public const MONITORING      = 'monitors';
    public const OPERATOR        = 'operators';
    public const SUPERVISOR      = 'supervisors';
    public const ENUMERATOR      = 'enumerators';

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

    /**
     * @return array
     */
    public static function assignable(): array
    {
        return Helper::arrayExceptByValue(self::all(), [self::SUPER_ADMIN]);
    }
}
