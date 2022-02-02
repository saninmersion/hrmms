<?php

namespace App\Domain\Applications\Services\Stats;

/**
 * Interface StatsInterface
 * @package App\Domain\Applications\Services\Stats
 */
interface StatsInterface
{
    public function set(): void;

    /**
     * @return array|string|null
     */
    public function get();
}
