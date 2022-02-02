<?php

namespace App\Domain\Applications\Services\Stats;

use App\Domain\Applications\RedisCache\ApplicationStatsRedisCache;
use App\Domain\Applications\Repositories\ApplicationRepository;
use Illuminate\Support\Collection;

/**
 * Class DistrictWiseStats
 * @package App\Domain\Applications\Services\Stats
 */
class DistrictWiseStats implements StatsInterface
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
     * DistrictWiseStats constructor.
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
        return $this->redisCache->cacheArray(ApplicationStatsRedisCache::DISTRICTS_WISE);
    }

    public function set(): void
    {
        $districts           = [];
        $districts['first']  = $this->repository->getDistrictsStats('first');
        $districts['second'] = $this->repository->getDistrictsStats('second');

        $stats = [];
        foreach ($districts as $priority => $districtStats) {
            $stats[$priority] = $districtStats->groupBy('district')->map(
                function (Collection $stats, $district) {
                    if ( !$district ) {
                        return null;
                    }

                    return $stats->flatMap(
                        fn($data) => [$data->application_post => $data->applicant_count]
                    );
                }
            )->filter()->toArray();
        }

        $this->redisCache->cacheArray(ApplicationStatsRedisCache::DISTRICTS_WISE, $stats);
    }
}
