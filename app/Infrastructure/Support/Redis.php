<?php

namespace App\Infrastructure\Support;

use Illuminate\Support\Facades\Redis as BaseRedis;

/**
 * Class Redis
 * @package App\Infrastructure\Support
 * @mixin \Redis
 */
class Redis
{
    /**
     * @param string $name
     * @param mixed  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, $arguments)
    {
        return BaseRedis::$name(...$arguments);
    }
}
