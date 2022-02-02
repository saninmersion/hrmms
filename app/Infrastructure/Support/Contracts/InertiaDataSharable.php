<?php

namespace App\Infrastructure\Support\Contracts;

use Closure;

/**
 * Interface InertiaDataSharable
 * @package App\Infrastructure\Support\Contracts
 */
interface InertiaDataSharable
{
    /**
     * @return string
     */
    public function key(): string;

    /**
     * @return Closure|array
     */
    public function data();
}
