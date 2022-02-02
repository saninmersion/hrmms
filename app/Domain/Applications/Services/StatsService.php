<?php

namespace App\Domain\Applications\Services;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Municipality;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Domain\Applicants\Repositories\ShortListedApplicantRepository;
use App\Domain\Applications\Services\Stats\DailyTrendStats;
use App\Domain\Applications\Services\Stats\DistrictWiseGenderStats;
use App\Domain\Applications\Services\Stats\DistrictWiseOverallStats;
use App\Domain\Applications\Services\Stats\DistrictWiseStats;
use App\Domain\Applications\Services\Stats\GenderBasedStats;
use App\Domain\Applications\Services\Stats\MunicipalityStats;
use App\Domain\Applications\Services\Stats\OverallStats;
use App\Domain\Applications\Services\Stats\StatsDateTime;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;

/**
 * Class StatsService
 * @package App\Domain\Applications\Services
 */
class StatsService
{
    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;
    /**
     * @var ShortListedApplicantRepository
     */
    protected ShortListedApplicantRepository $shortListedApplicantRepository;

    public function __construct(DistrictRepository $districtRepository, ShortListedApplicantRepository $shortListedApplicantRepository)
    {
        $this->districtRepository             = $districtRepository;
        $this->shortListedApplicantRepository = $shortListedApplicantRepository;
    }

    /**
     * @return OverallStats
     * @throws BindingResolutionException
     */
    public function overall(): OverallStats
    {
        return app()->make(OverallStats::class);
    }

    /**
     * @return GenderBasedStats
     * @throws BindingResolutionException
     */
    public function genderBased(): GenderBasedStats
    {
        return app()->make(GenderBasedStats::class);
    }

    /**
     * @return DailyTrendStats
     * @throws BindingResolutionException
     */
    public function dailyTrend(): DailyTrendStats
    {
        return app()->make(DailyTrendStats::class);
    }

    /**
     * @return DistrictWiseStats
     * @throws BindingResolutionException
     */
    public function districtWise(): DistrictWiseStats
    {
        return app()->make(DistrictWiseStats::class);
    }

    /**
     * @return StatsDateTime
     * @throws BindingResolutionException
     */
    public function statsDateTime(): StatsDateTime
    {
        return app()->make(StatsDateTime::class);
    }

    /**
     * @param int|null $districtCode
     *
     * @return DistrictWiseOverallStats
     * @throws BindingResolutionException
     */
    public function overallByDistrict(?int $districtCode = null): DistrictWiseOverallStats
    {
        return app()->make(DistrictWiseOverallStats::class, ['districtCode' => $districtCode]);
    }

    /**
     * @param int|null $districtCode
     *
     * @return DistrictWiseGenderStats
     * @throws BindingResolutionException
     */
    public function genderBasedByDistrict(?int $districtCode = null): DistrictWiseGenderStats
    {
        return app()->make(DistrictWiseGenderStats::class, ['districtCode' => $districtCode]);
    }

    /**
     * @param int|null $districtCode
     *
     * @return MunicipalityStats
     * @throws BindingResolutionException
     */
    public function municipalityStatsByDistrict(?int $districtCode = null): MunicipalityStats
    {
        return app()->make(MunicipalityStats::class, ['districtCode' => $districtCode]);
    }

    /**
     * @param int $districtCode
     *
     * @return array
     */
    public function shortlistedStats(int $districtCode): array
    {
        $stats = [];
        /** @var District $district */
        $district       = $this->districtRepository->with(['municipalities', 'municipalities.shortlists'])->findByField('code', $districtCode)->first();
        $municipalities = $district->municipalities;
        $stats          = $municipalities->reduce(
            function (array $tmpStats, Municipality $municipality) {
                $shortlist                     = $municipality->shortlists;
                $grouped                       = $shortlist->groupBy(
                    function (ShortListedApplicant $item) {
                        return $item->role;
                    }
                );
                $hired                         = $grouped->map(
                    function (Collection $item) {
                        $stats                = $item->countBy(
                            function (ShortListedApplicant $item) {
                                return $item->hiring_status;
                            }
                        );
                        $stats['total_count'] = $item->count();

                        return $stats;
                    }
                )->toArray();
                $hired['count']                = $shortlist->count();
                $tmpStats[$municipality->code] = $hired;

                return $tmpStats;
            },
            $stats
        );

        return $stats;
    }

    /**
     * @return array
     */
    public function shortlistedStatsByDistrict(): array
    {
        $stats = $this->shortListedApplicantRepository->getHiredAndRejectedCountByDistrictAndRole()->reduce(
            function ($stats, $item) {
                $stats[$item->district_code][$item->role][$item->hiring_status] = $item->count;

                return $stats;
            }
        );

        return $stats ?? [];
    }
}
