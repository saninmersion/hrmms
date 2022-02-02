<?php

namespace App\Domain\Applications\Services\Stats;

use App\Domain\Applications\RedisCache\ApplicationStatsRedisCache;
use App\Domain\Applications\Repositories\ApplicationRepository;
use Illuminate\Support\Collection;

/**
 * Class DailyTrendStats
 * @package App\Domain\Applications\Services\Stats
 */
class DailyTrendStats implements StatsInterface
{
    /**
     * @var ApplicationRepository
     */
    protected ApplicationRepository $repository;
    /**
     * @var ApplicationStatsRedisCache
     */
    protected ApplicationStatsRedisCache $redisCache;

    /**
     * DailyTrendStats constructor.
     *
     * @param ApplicationRepository      $repository
     * @param ApplicationStatsRedisCache $redisCache
     */
    public function __construct(ApplicationRepository $repository, ApplicationStatsRedisCache $redisCache)
    {
        $this->repository = $repository;
        $this->redisCache = $redisCache;
    }

    /**
     * @return array|null
     */
    public function get(): ?array
    {
        return $this->redisCache->cacheArray(ApplicationStatsRedisCache::DAILY);
    }

    public function set(): void
    {
        $daily = $this->repository->getDailyStats();

        $stats = $daily->groupBy('apply_date')->map(
            fn(Collection $stats) => $stats->flatMap(
                fn($data) => [$data->application_post => data_get($data, 'applicant_count', 0)]
            )
        )->toArray();

        $this->redisCache->cacheArray(ApplicationStatsRedisCache::DAILY, $stats);
    }
}
