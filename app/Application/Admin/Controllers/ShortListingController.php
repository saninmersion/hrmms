<?php

namespace App\Application\Admin\Controllers;

use App\Application\Admin\Requests\ShortlistingActionRequest;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applicants\Criteria\ShortlistedApplicantListCriteria;
use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Domain\Applicants\Presenters\ShortListedApplicantDetailPresenter;
use App\Domain\Applicants\Presenters\ShortListedApplicantListPresenter;
use App\Domain\Applicants\Repositories\ApplicationListRepository;
use App\Domain\Applicants\Repositories\ShortListedApplicantRepository;
use App\Domain\Applicants\Requests\ShortListing\HiringStatusChangeRequest;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Constants\HiringStatus;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Response;
use Inertia\ResponseFactory;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class ShortListingController
 * @package App\Application\Admin\Controllers
 */
class ShortListingController extends AdminController
{
    /**
     * @var ShortListedApplicantRepository
     */
    protected ShortListedApplicantRepository $shortListedApplicantRepository;
    /**
     * @var ApplicationListRepository
     */
    protected ApplicationListRepository $applicationListRepository;

    /**
     * ShortListingController constructor.
     *
     * @param ShortListedApplicantRepository $shortListedApplicantRepository
     * @param ApplicationListRepository      $applicationListRepository
     */
    public function __construct(ShortListedApplicantRepository $shortListedApplicantRepository, ApplicationListRepository $applicationListRepository)
    {
        $this->shortListedApplicantRepository = $shortListedApplicantRepository;
        $this->applicationListRepository      = $applicationListRepository;
    }

    /**
     * @param Request            $request
     * @param DistrictRepository $districtRepository
     *
     * @return Response|ResponseFactory
     * @throws RepositoryException
     */
    public function index(Request $request, DistrictRepository $districtRepository)
    {
        $queries = $request->all();
        $perPage = $request->input('per_page', General::paginateDefault());

        $district     = $queries['district'] ?? null;
        $municipality = $queries['municipality'] ?? null;
        $role         = $queries['role'] ?? null;

        if ( $district && $municipality && $role && in_array($role, [ApplicationType::SUPERVISOR, ApplicationType::ENUMERATOR]) ) {
            $this->applicationListRepository->pushCriteria(new ShortlistedApplicantListCriteria($queries));
            $this->applicationListRepository->setPresenter(ShortListedApplicantListPresenter::class);
            $applicants = $this->applicationListRepository->getShortlisted($municipality, $role)->paginate($perPage);
            $meta       = array_pop($applicants);
        }

        return inertia(
            'ShortListing/List',
            [
                'options'    => [
                    'districts'        => $districtRepository->allDistrictsWithMunicipalitiesOptions(),
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
     * @return Response|ResponseFactory
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
        $this->applicationListRepository->setPresenter(ShortListedApplicantDetailPresenter::class);
        $applicant = $this->applicationListRepository->find($applicantId);
        $options   = [
            'hiringOptions' => HiringStatus::all(),
        ];

        return inertia('ShortListing/Show', compact('applicant', 'options', 'role'));
    }

    /**
     * @param int                       $applicantId
     * @param ShortlistingActionRequest $request
     *
     * @return RedirectResponse
     */
    public function shortlist($applicantId, ShortlistingActionRequest $request)
    {
        $this->shortListedApplicantRepository->updateOrCreate(
            [
                'applicant_id'      => $applicantId,
                'municipality_code' => $request->input('municipality'),
                'role'              => $request->input('role'),
            ],
            [
                'is_shortlisted' => true,
            ]
        );

        return redirect()->back();
    }

    /**
     * @param int                       $applicantId
     * @param ShortlistingActionRequest $request
     *
     * @return RedirectResponse
     */
    public function unShortlist($applicantId, ShortlistingActionRequest $request)
    {
        $this->shortListedApplicantRepository->updateOrCreate(
            [
                'applicant_id'      => $applicantId,
                'municipality_code' => $request->input('municipality'),
                'role'              => $request->input('role'),
            ],
            [
                'is_shortlisted' => false,
            ]
        );

        return redirect()->back();
    }

    /**
     * @param int                       $applicantId
     * @param string                    $role
     * @param HiringStatusChangeRequest $request
     *
     * @return RedirectResponse
     */
    public function changeHiringStatus($applicantId, $role, HiringStatusChangeRequest $request): RedirectResponse
    {
        /** @var ShortListedApplicant $shortListed */
        $shortListed = $request->setForm()->update((int) $applicantId, $role);

        session()->flash("Hiring Status Changed");

        $params = [
            'district'     => $shortListed->municipality ? $shortListed->municipality->district_code : '',
            'municipality' => $shortListed->municipality_code,
            'role'         => $shortListed->role,
        ];
        /** @var User $currentUser */
        $currentUser = AuthHelper::currentUser();

        if ( $currentUser->role === AuthRoles::DISTRICT_STAFFS ) {
            return redirect()->route(
                'admin.shortlisting.district.index',
                $params
            );
        }

        return redirect()->route(
            'admin.shortlisting.index',
            $params
        );
    }
}
