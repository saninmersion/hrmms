<?php

namespace App\Domain\Applications\Services\Stats;

use App\Domain\Applications\RedisCache\ApplicationStatsRedisCache;

/**
 * Class StatsDateTime
 * @package App\Domain\Applications\Services\Stats
 */
class StatsDateTime implements StatsInterface
{
    /**
     * @var ApplicationStatsRedisCache
     */
    protected ApplicationStatsRedisCache $redisCache;

    /**
     * StatsDateTime constructor.
     *
     * @param ApplicationStatsRedisCache $redisCache
     */
    public function __construct(ApplicationStatsRedisCache $redisCache)
    {
        $this->redisCache = $redisCache;
    }

    /**
     * @return string|null
     */
    public function get(): ?string
    {
        return $this->redisCache->cacheString(ApplicationStatsRedisCache::STATS_TIME);
    }

    public function set(): void
    {
        $time = now()->toDateTimeString();

        $this->redisCache->cacheString(ApplicationStatsRedisCache::STATS_TIME, $time);
    }
}
