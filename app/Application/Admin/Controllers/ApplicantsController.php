<?php

namespace App\Application\Admin\Controllers;

use App\Application\Admin\Support\ApplicantFilterOptions;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applicants\Criteria\ApplicantListCriteria;
use App\Domain\Applicants\Criteria\ApplicantOfflineListCriteria;
use App\Domain\Applicants\Presenters\ApplicantDetailPresenter;
use App\Domain\Applicants\Presenters\ApplicantListPresenter;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class ApplicantsController
 * @package App\Application\Admin\Controllers
 */
class ApplicantsController extends AdminController
{
    /**
     * @var ApplicantRepository
     */
    protected ApplicantRepository $applicantRepository;

    /**
     * @var DistrictRepository
     */
    protected DistrictRepository $districtRepository;

    /**
     * ApplicantsController constructor.
     *
     * @param ApplicantRepository $applicantRepository
     * @param DistrictRepository  $districtRepository
     */
    public function __construct(ApplicantRepository $applicantRepository, DistrictRepository $districtRepository)
    {
        $this->applicantRepository = $applicantRepository;
        $this->districtRepository  = $districtRepository;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     * @throws RepositoryException
     */
    public function index(Request $request)
    {
        $queries = $request->all();
        $perPage = $request->input('per_page', General::paginateDefault());

        $this->applicantRepository->pushCriteria(new ApplicantListCriteria($queries));
        $this->applicantRepository->setPresenter(ApplicantListPresenter::class);
        $this->applicantRepository->with(['application', 'application.first_priority_district', 'application.second_priority_district', 'citizenship_issued_district', 'verification']);

        $applicants = $this->applicantRepository->orderBy('created_at', 'desc')->paginate($perPage);
        $meta       = array_pop($applicants);

        $options = ApplicantFilterOptions::options();

        return inertia(
            'Applicants/List',
            [
                'applicants' => $applicants,
                'pagination' => $meta['pagination'],
                'options'    => $options ?: null,
                'queries'    => $queries ?: null,
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     * @throws RepositoryException
     */
    public function indexOffline(Request $request)
    {
        $queries = $request->all();
        $perPage = $request->input('per_page', General::paginateDefault());

        $this->applicantRepository->pushCriteria(new ApplicantOfflineListCriteria($queries));
        $this->applicantRepository->setPresenter(ApplicantListPresenter::class);
        $this->applicantRepository->with(['application', 'application.first_priority_district', 'application.second_priority_district', 'citizenship_issued_district', 'verification']);
        /** @var User $currentUser */
        $currentUser = AuthHelper::currentUser();

        if ( in_array($currentUser->role, [AuthRoles::OPERATOR, AuthRoles::DISTRICT_STAFFS]) ) {
            $applicants = $this->applicantRepository->byOperator($currentUser->id)->orderBy('created_at', 'desc')->paginate($perPage);
        } else {
            $applicants = $this->applicantRepository->byOfflineStatus(true)->orderBy('created_at', 'desc')->paginate($perPage);
        }

        $meta = array_pop($applicants);

        $options = ApplicantFilterOptions::options();

        return inertia(
            'Applicants/OfflineList',
            [
                'applicants' => $applicants,
                'pagination' => $meta['pagination'],
                'options'    => $options ?: null,
                'queries'    => $queries ?: null,
            ]
        );
    }

    /**
     * @param int $applicantId
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($applicantId)
    {
        $this->applicantRepository->setPresenter(ApplicantDetailPresenter::class);
        $applicant = $this->applicantRepository->find((int) $applicantId);

        return inertia('Applicants/ApplicantDetail', compact('applicant'));
    }
}
