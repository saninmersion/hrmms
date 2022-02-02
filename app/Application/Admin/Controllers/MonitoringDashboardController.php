<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Monitorings\Repositories\MonitoringRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;

/**
 * Class MonitoringDashboardController
 * @package App\Application\Admin\Controllers
 */
class MonitoringDashboardController extends \App\Infrastructure\Support\Controller\AdminController
{
    /**
     * @var MonitoringRepository
     */
    protected MonitoringRepository $monitoringRepository;
    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;

    public function __construct(MonitoringRepository $monitoringRepository, DistrictRepository $districtRepository)
    {
        $this->monitoringRepository = $monitoringRepository;
        $this->districtRepository   = $districtRepository;
    }

    /**
     * @return \Inertia\Response|\Inertia\ResponseFactory|void
     */
    public function show()
    {
        /** @var User $user */
        $user = AuthHelper::currentUser();

        $formStats         = [];
        $municipalityStats = [];

        $formTypes = ApplicationConstants::monitoringForms();
        $dateTime  = now()->toDayDateTimeString();

        if ( $user->role === AuthRoles::DISTRICT_STAFFS ) {
            $district                  = $this->districtRepository->getByDistrictCode((int) $user->district_code);
            $district['district_code'] = (int) $user->district_code;
            $formStats                 = $this->monitoringRepository->countByFormForDistrict((int) $user->district_code);
            $municipalityStats         = $this->monitoringRepository->countByFormAndMunicipalityForDistrict((int) $user->district_code);

            return inertia('Monitorings/MonitoringDashboard', compact('formStats', 'municipalityStats', 'district', 'formTypes', 'dateTime'));
        }

        if ( $user->role === AuthRoles::ADMIN || $user->role === AuthRoles::SUPER_ADMIN ) {
            $districts         = $this->districtRepository->allDistrictsWithMunicipalitiesOptions();
            $formStats         = $this->monitoringRepository->countByFormAndDistrict();
            $municipalityStats = $this->monitoringRepository->countByFormAndMunicipality();

            return inertia('Monitorings/MonitoringAdminDashboard', compact('formStats', 'municipalityStats', 'districts', 'formTypes', 'dateTime'));
        }
    }
}
