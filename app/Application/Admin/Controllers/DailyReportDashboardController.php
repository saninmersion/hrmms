<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\CensusOffices\Repositories\CensusOfficeRepository;
use App\Domain\DailyReports\Repositories\DailyReportRepository;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Contracts\Container\BindingResolutionException;
use Inertia\Response;
use Inertia\ResponseFactory;

/**
 * Class DailyReportDashboardController
 * @package App\Application\Admin\Controllers
 */
class DailyReportDashboardController extends AdminController
{
    /**
     * @var DailyReportRepository
     */
    protected DailyReportRepository $dailyReportRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * DailyReportDashboardController constructor.
     *
     * @param DailyReportRepository $dailyReportRepository
     * @param UserRepository        $userRepository
     */
    public function __construct(DailyReportRepository $dailyReportRepository, UserRepository $userRepository)
    {
        $this->dailyReportRepository = $dailyReportRepository;
        $this->userRepository        = $userRepository;
    }

    /**
     * @return Response|ResponseFactory|void
     *
     * @throws BindingResolutionException
     */
    public function __invoke()
    {
        /** @var User $user */
        $user          = AuthHelper::currentUser();
        $districts     = app()->make(DistrictRepository::class)->allDistrictsWithMunicipalitiesOptions()->keyBy('code');
        $censusOffices = app()->make(CensusOfficeRepository::class)->all()->keyBy('id');

        if ( $user->role === AuthRoles::DISTRICT_STAFFS ) {
            $dailyReportStats              = $this->dailyReportRepository->getDistrictAdminStats((int) $user->district_code);
            $dates                         = $dailyReportStats->unique('date')->sortBy('date', SORT_REGULAR, true)->pluck('date')->toArray();
            $dailyReportDistrictAdminStats = $dailyReportStats->groupBy(['census_office_id', 'created_by', 'enumerator_id', 'date'])->toArray();
            $supervisors                   = $this->userRepository->with('enumerators')->byRole(AuthRoles::SUPERVISOR)->all()->keyBy('id');

            return inertia(
                'DailyReports/DailyReportDistrictDashboard',
                [
                    'stats'         => $dailyReportDistrictAdminStats,
                    'districts'     => $districts,
                    'censusOffices' => $censusOffices,
                    'dates'         => $dates,
                    'users'         => $supervisors,
                ]
            );
        }

        if ( $user->role === AuthRoles::ADMIN || $user->role === AuthRoles::SUPER_ADMIN ) {
            $dailyReportStats      = $this->dailyReportRepository->getAdminStats();
            $dates                 = $dailyReportStats->unique('date')->sortBy('date', SORT_REGULAR, true)->pluck('date')->toArray();
            $dailyReportAdminStats = $dailyReportStats->groupBy(['district_code', 'census_office_id', 'date'])->toArray();

            return inertia(
                'DailyReports/DailyReportAdminDashboard',
                [
                    'stats'         => $dailyReportAdminStats,
                    'districts'     => $districts,
                    'censusOffices' => $censusOffices,
                    'dates'         => $dates,
                ]
            );
        }
    }
}
