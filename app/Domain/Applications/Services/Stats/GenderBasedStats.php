<?php

namespace App\Domain\Applications\Services\Stats;

use App\Domain\Applications\RedisCache\ApplicationStatsRedisCache;
use App\Domain\Applications\Repositories\ApplicationRepository;
use Illuminate\Support\Collection;

/**
 * Class GenderBasedStats
 * @package App\Domain\Applications\Services\Stats
 */
class GenderBasedStats implements StatsInterface
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
     * GenderBasedStats constructor.
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
        return $this->redisCache->cacheArray(ApplicationStatsRedisCache::GENDER_BASED);
    }

    public function set(): void
    {
        $genderBased = $this->repository->getGenderBasedStats();

        $stats = $genderBased->groupBy('gender')->map(
            fn(Collection $stats) => $stats->flatMap(
                fn($data) => [$data->application_post => $data->applicant_count]
            )
        )->toArray();

        $this->redisCache->cacheArray(ApplicationStatsRedisCache::GENDER_BASED, $stats);
    }
}
