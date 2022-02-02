<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Models\District;
use App\Domain\AdminDivisions\Models\Province;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\AdminDivisions\Repositories\ProvinceRepository;
use App\Domain\CensusOffices\Repositories\CensusOfficeRepository;
use App\Domain\HouseDailyReports\Repositories\HouseDailyReportRepository;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Contracts\Container\BindingResolutionException;
use Inertia\Response;
use Inertia\ResponseFactory;

/**
 * Class HouseDailyReportDashboardController
 *
 * @package App\Application\Admin\Controllers
 */
class HouseDailyReportDashboardController extends AdminController
{
    /**
     * @var HouseDailyReportRepository
     */
    protected HouseDailyReportRepository $dailyReportRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;
    /**
     * @var \App\Domain\AdminDivisions\Repositories\ProvinceRepository
     */
    private ProvinceRepository $provinceRepository;

    /**
     * HouseDailyReportDashboardController constructor.
     *
     * @param HouseDailyReportRepository $dailyReportRepository
     * @param UserRepository             $userRepository
     */
    public function __construct(HouseDailyReportRepository $dailyReportRepository, UserRepository $userRepository, ProvinceRepository $provinceRepository)
    {
        $this->dailyReportRepository = $dailyReportRepository;
        $this->userRepository        = $userRepository;
        $this->provinceRepository    = $provinceRepository;
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
            $dailyReportDistrictAdminStats = $dailyReportStats->groupBy(['census_office_id', 'created_by', 'date'])->toArray();
            $supervisors                   = $this->userRepository->byRole(AuthRoles::SUPERVISOR)->all()->keyBy('id');

            return inertia(
                'HouseDailyReports/DailyReportDistrictDashboard',
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
            $dailyReportAdminStats = $dailyReportStats->groupBy(['district_code', 'census_office_id', 'date']);
            $provinces             = collect($this->provinceRepository->with('districts:province_code,code')->all());
            $provinceStats         = $provinces->mapWithKeys(
                function (Province $province) use ($dailyReportAdminStats) {
                    $provinceDistrictStats = $province->districts->mapWithKeys(fn(District $district) => [$district->code => $dailyReportAdminStats->get($district->code)]);

                    return [
                        $province->code => [
                            'code'      => $province->code,
                            'title_en'  => $province->title_en,
                            'title_ne'  => $province->title_ne,
                            'districts' => $provinceDistrictStats->toArray(),
                        ],
                    ];
                }
            );

            return inertia(
                'HouseDailyReports/DailyReportAdminDashboard',
                [
                    'stats'         => $provinceStats,
                    'districts'     => $districts,
                    'provinceStats' => $provinceStats,
                    'censusOffices' => $censusOffices,
                    'dates'         => $dates,
                ]
            );
        }
    }
}
