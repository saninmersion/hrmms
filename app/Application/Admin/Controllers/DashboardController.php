<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applications\Services\StatsService;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use App\Infrastructure\Support\Helper;
use Illuminate\Contracts\Container\BindingResolutionException;
use Inertia\Response;
use Inertia\ResponseFactory;

/**
 * Class DashboardController
 * @package App\Application\Admin\Controllers
 */
class DashboardController extends AdminController
{
    protected StatsService $statsService;
    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;

    /**
     * DashboardController constructor.
     *
     * @param StatsService       $statsService
     * @param DistrictRepository $districtRepository
     */
    public function __construct(StatsService $statsService, DistrictRepository $districtRepository)
    {
        $this->statsService       = $statsService;
        $this->districtRepository = $districtRepository;
    }

    /**
     * @return Response|ResponseFactory
     * @throws BindingResolutionException
     */
    public function __invoke()
    {
        /** @var User $user */
        $user = AuthHelper::currentUser();

        if ( $user->role === AuthRoles::DISTRICT_STAFFS ) {
            $district = $this->districtRepository->getByDistrictCode((int) $user->district_code);

            [$overallStats, $genderDistribution, $dateTime, $municipalitiesStats, $hiringStats] = $this->getStatsForDistrict((int) $user->district_code);

            return inertia(
                'Dashboard/DistrictUserDashboard',
                compact('overallStats', 'genderDistribution', 'dateTime', 'municipalitiesStats', 'district', 'hiringStats')
            );
        }

        $overallStats       = $this->statsService->overall()->get();
        $genderDistribution = $this->statsService->genderBased()->get();
        $dailyTrend         = $this->statsService->dailyTrend()->get();
        $dateTime           = Helper::parseDateTime($this->statsService->statsDateTime()->get());

        return inertia(
            'Dashboard/Dashboard',
            compact(
                'overallStats',
                'genderDistribution',
                'dailyTrend',
                'dateTime',
            )
        );
    }

    /**
     * @param int $districtCode
     *
     * @return array
     * @throws BindingResolutionException
     */
    protected function getStatsForDistrict(int $districtCode): array
    {
        return [
            $this->statsService->overallByDistrict((int) $districtCode)->get(),
            $this->statsService->genderBasedByDistrict((int) $districtCode)->get(),
            Helper::parseDateTime($this->statsService->statsDateTime()->get()),
            $this->statsService->municipalityStatsByDistrict((int) $districtCode)->get(),
            $this->statsService->shortlistedStats((int) $districtCode),
        ];
    }
}
