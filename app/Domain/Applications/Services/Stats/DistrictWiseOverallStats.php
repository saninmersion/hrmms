<?php

namespace App\Domain\Applications\Services\Stats;

use App\Domain\Applications\RedisCache\ApplicationStatsRedisCache;
use App\Domain\Applications\Repositories\ApplicationRepository;
use Illuminate\Support\Collection;

/**
 * Class DistrictWiseOverallStats
 * @package App\Domain\Applications\Services\Stats
 */
class DistrictWiseOverallStats implements StatsInterface
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
     * @var int|null
     */
    protected ?int $districtCode;

    /**
     * DistrictWiseOverallStats constructor.
     *
     * @param int|null                   $districtCode
     * @param ApplicationRepository      $repository
     * @param ApplicationStatsRedisCache $redisCache
     */
    public function __construct(ApplicationRepository $repository, ApplicationStatsRedisCache $redisCache, ?int $districtCode = null)
    {
        $this->repository   = $repository;
        $this->redisCache   = $redisCache;
        $this->districtCode = $districtCode;
    }

    /**
     * @return array|null
     */
    public function get(): ?array
    {
        if ( is_null($this->districtCode) ) {
            return null;
        }

        return $this->redisCache->districtWise(ApplicationStatsRedisCache::OVERALL_DISTRICT_WISE, $this->districtCode);
    }

    public function set(): void
    {
        $stats = $this->repository->getOverAllStatsByDistrict();

        $stats->groupBy(['district', 'status', 'application_post'])->map(
            function (Collection $districtWise, int $districtCode) {
                $data = $districtWise->map(
                    fn(Collection $statusWise) => $statusWise->map(
                        fn(Collection $data) => $data->first()->applicant_count ?? 0
                    )
                )->toArray();

                $this->redisCache->districtWise(ApplicationStatsRedisCache::OVERALL_DISTRICT_WISE, $districtCode, $data);
            }
        );
    }
}
