<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applications\Services\StatsService;
use App\Infrastructure\Support\Controller\AdminController;
use App\Infrastructure\Support\Helper;
use Illuminate\Contracts\Container\BindingResolutionException;
use Inertia\Response;
use Inertia\ResponseFactory;

/**
 * Class DistrictDashboardController
 *
 * @package App\Application\Admin\Controllers
 */
class DistrictDashboardController extends AdminController
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
    public function index()
    {
        $districtWise = $this->statsService->districtWise()->get();
        $dateTime     = $this->statsService->statsDateTime()->get();
        $districts    = $this->districtRepository->allDistrictForOptions();
        $hiredStats   = $this->statsService->shortlistedStatsByDistrict();

        return inertia(
            'Dashboard/DistrictDashboard',
            compact(
                'districtWise',
                'dateTime',
                'districts',
                'hiredStats',
            )
        );
    }

    /**
     * @param int $districtCode
     *
     * @return Response|ResponseFactory
     * @throws BindingResolutionException
     */
    public function show($districtCode)
    {
        $districts = $this->districtRepository->allDistrictForOptions();
        $district  = $this->districtRepository->getByDistrictCode((int) $districtCode);

        if ( !$district ) {
            abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        $title = app()->getLocale() === 'en' ? $district['title_en'] : $district['title_ne'];

        [$overallStats, $genderDistribution, $dateTime, $municipalitiesStats, $hiringStats] = $this->getStatsForDistrict((int) $districtCode);

        return inertia(
            'Dashboard/ShowDistrict',
            compact('overallStats', 'genderDistribution', 'dateTime', 'municipalitiesStats', 'title', 'districts', 'district', 'hiringStats')
        );
    }

    /**
     * @param int $districtCode
     *
     * @return array
     * @throws BindingResolutionException
     */
    protected function getStatsForDistrict(int $districtCode)
    : array
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
