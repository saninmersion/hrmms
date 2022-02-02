<?php

namespace App\Domain\Applications\Services\Stats;

use App\Domain\Applications\RedisCache\ApplicationStatsRedisCache;
use App\Domain\Applications\Repositories\ApplicationRepository;
use Illuminate\Support\Collection;

/**
 * Class MunicipalityStats
 * @package App\Domain\Applications\Services\Stats
 */
class MunicipalityStats implements StatsInterface
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
     * MunicipalityStats constructor.
     *
     * @param ApplicationRepository      $repository
     * @param ApplicationStatsRedisCache $redisCache
     * @param int|null                   $districtCode
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
        return $this->redisCache->districtWise(ApplicationStatsRedisCache::MUNICIPALITY_WISE, $this->districtCode);
    }

    public function set(): void
    {
        $districts           = [];
        $districts['first']  = $this->repository->getMunicipalityStats('first');
        $districts['second'] = $this->repository->getMunicipalityStats('second');

        $stats = [];
        foreach ($districts as $priority => $districtStats) {
            $stats = $districtStats->groupBy(['district', 'municipality'])->reduce(
                function (array $districtStats, Collection $districtWise, int $districtCode) use ($priority) {
                    $stats = $districtWise->map(
                        fn(Collection $municipalityStats) => $municipalityStats->flatMap(
                            fn($data) => [$data->application_post => $data->applicant_count]
                        )
                    )->filter()->toArray();

                    $districtStats[$districtCode][$priority] = $stats;

                    return $districtStats;
                },
                $stats
            );
        }

        foreach ($stats as $districtCode => $data) {
            $this->redisCache->districtWise(ApplicationStatsRedisCache::MUNICIPALITY_WISE, $districtCode, $data);
        }
    }
}
