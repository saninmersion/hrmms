<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applicants\Criteria\ShortlistedApplicantDistrictListCriteria;
use App\Domain\Applicants\Presenters\ShortListedApplicantDistrictListPresenter;
use App\Domain\Applicants\Presenters\ShortListedApplicantDistrictPresenter;
use App\Domain\Applicants\Repositories\ApplicationListRepository;
use App\Domain\Applicants\Repositories\ShortListedApplicantRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Constants\HiringStatus;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class DistrictShortListController
 * @package App\Application\Admin\Controllers
 */
class DistrictShortListController extends AdminController
{
    /**
     * @var ShortListedApplicantRepository
     */
    protected ShortListedApplicantRepository $shortListedApplicantRepository;
    /**
     * @var ApplicationListRepository
     */
    private ApplicationListRepository $applicationListRepository;

    /**
     * DistrictShortListController constructor.
     *
     * @param ApplicationListRepository      $applicationListRepository
     * @param ShortListedApplicantRepository $shortListedApplicantRepository
     */
    public function __construct(ApplicationListRepository $applicationListRepository, ShortListedApplicantRepository $shortListedApplicantRepository)
    {
        $this->applicationListRepository      = $applicationListRepository;
        $this->shortListedApplicantRepository = $shortListedApplicantRepository;
    }

    /**
     * @param Request            $request
     * @param DistrictRepository $districtRepository
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request, DistrictRepository $districtRepository)
    {
        $queries = $request->all();
        $perPage = $request->input('per_page', General::paginateDefault());

        $district     = $queries['district'] ?? null;
        $municipality = $queries['municipality'] ?? null;
        $role         = $queries['role'] ?? null;

        if ( $district && $municipality && $role && in_array($role, [ApplicationType::SUPERVISOR, ApplicationType::ENUMERATOR]) ) {
            $this->shortListedApplicantRepository->pushCriteria(new ShortlistedApplicantDistrictListCriteria($queries));
            $this->shortListedApplicantRepository->setPresenter(ShortListedApplicantDistrictListPresenter::class);
            $applicants = $this->shortListedApplicantRepository->byShortlistedStatus(true)->paginate($perPage);
            $meta       = array_pop($applicants);
        }
        /** @var User $user */
        $user = AuthHelper::currentUser();

        if ( $user->role === AuthRoles::DISTRICT_STAFFS ) {
            $district         = $districtRepository->getByDistrictCode($user->district_code);
            $district['code'] = $user->district_code;
            $districts        = [$district];
        } else {
            $districts = $districtRepository->allDistrictsWithMunicipalitiesOptions();
        }

        return inertia(
            'ShortListing/ShortlistedApplicantDistrictList',
            [
                'options'    => [
                    'districts'        => $districts,
                    'applicationTypes' => [ApplicationType::ENUMERATOR, ApplicationType::SUPERVISOR],
                ],
                'queries'    => $queries ?: null,
                'applicants' => $applicants ?? [],
                'pagination' => $meta['pagination'] ?? null,
            ]
        );
    }

    /**
     * @param int    $applicantId
     * @param string $role
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show(int $applicantId, string $role)
    {
        /** @var Collection $shortListedApplicant */
        $shortListedApplicant = $this->shortListedApplicantRepository->findWhere(
            [
                'applicant_id' => $applicantId,
                'role'         => $role,
            ]
        );
        if ( $shortListedApplicant->isEmpty() ) {
            abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
        }
        $this->applicationListRepository->setPresenter(ShortListedApplicantDistrictPresenter::class);
        $applicant = $this->applicationListRepository->find($applicantId);
        $options   = [
            'hiringOptions' => HiringStatus::all(),
        ];

        return inertia('ShortListing/District/ShortlistedApplicantDetail', compact('applicant', 'options', 'role'));
    }
}
