<?php

namespace App\Domain\Applications\Services\Stats;

use App\Domain\Applications\RedisCache\ApplicationStatsRedisCache;
use App\Domain\Applications\Repositories\ApplicationRepository;
use Illuminate\Support\Collection;

/**
 * Class DistrictWiseGenderStats
 * @package App\Domain\Applications\Services\Stats
 */
class DistrictWiseGenderStats implements StatsInterface
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
     * DistrictWiseGenderStats constructor.
     *
     * @param int|null                   $districtCode
     * @param ApplicationRepository      $repository
     * @param ApplicationStatsRedisCache $redisCache
     */
    public function __construct( ApplicationRepository $repository, ApplicationStatsRedisCache $redisCache, ?int $districtCode = null )
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

        return $this->redisCache->districtWise(ApplicationStatsRedisCache::GENDER_DISTRICT_WISE, $this->districtCode);
    }

    public function set(): void
    {
        $stats = $this->repository->getGenderBasedStatsByDistrict();

        $stats->groupBy(['district', 'gender', 'application_post'])->map(
            function (Collection $districtWise, int $districtCode) {
                $data = $districtWise->map(
                    fn(Collection $genderWise) => $genderWise->map(
                        fn(Collection $data) => $data->first()->applicant_count ?? 0
                    )
                )->toArray();

                $this->redisCache->districtWise(ApplicationStatsRedisCache::GENDER_DISTRICT_WISE, $districtCode, $data);
            }
        );
    }
}
