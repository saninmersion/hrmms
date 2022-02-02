<?php

namespace App\Domain\Applications\Services\Stats;

use App\Domain\Applications\RedisCache\ApplicationStatsRedisCache;
use App\Domain\Applications\Repositories\ApplicationRepository;
use Illuminate\Support\Collection;

/**
 * Class OverallStats
 * @package App\Domain\Applications\Services\Stats
 */
class OverallStats implements StatsInterface
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
     * OverallStats constructor.
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
        return $this->redisCache->cacheArray(ApplicationStatsRedisCache::OVERALL);
    }

    public function set(): void
    {
        $stats = $this->repository->getOverAllStats();

        $data = $stats->groupBy('status')->map(
            fn(Collection $stats) => $stats->flatMap(
                fn($data) => [$data->application_post => $data->applicant_count]
            )
        )->toArray();

        $this->redisCache->cacheArray(ApplicationStatsRedisCache::OVERALL, $data);
    }
}
