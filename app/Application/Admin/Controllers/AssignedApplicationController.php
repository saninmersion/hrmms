<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applicants\Criteria\ApplicationVerificationListCriteria;
use App\Domain\Applicants\Presenters\ApplicantListPresenter;
use App\Domain\Applicants\Presenters\ApplicationVerificationPresenter;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Repositories\ApplicantVerificationRepository;
use App\Domain\Applicants\Repositories\VerifierAssignmentRepository;
use App\Domain\Applicants\Requests\ApplicationVerification\ApplicationVerificationRequest;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\VerificationStatus;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Inertia\ResponseFactory;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class AssignedApplicationController
 * @package App\Application\Admin\Controllers
 */
class AssignedApplicationController extends AdminController
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
     * @var ApplicantVerificationRepository
     */
    protected ApplicantVerificationRepository $verificationRepository;
    /**
     * @var VerifierAssignmentRepository
     */
    protected VerifierAssignmentRepository $assignmentRepository;

    public function __construct(ApplicantRepository $applicantRepository, ApplicantVerificationRepository $verificationRepository, DistrictRepository $districtRepository, VerifierAssignmentRepository $assignmentRepository)
    {
        $this->applicantRepository    = $applicantRepository;
        $this->districtRepository     = $districtRepository;
        $this->verificationRepository = $verificationRepository;
        $this->assignmentRepository   = $assignmentRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response|ResponseFactory
     * @throws RepositoryException
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user    = AuthHelper::currentUser();
        $queries = $request->all();
        $perPage = $request->input('per_page', 15);

        try {
            $this->applicantRepository->pushCriteria(new ApplicationVerificationListCriteria($request->all()));
            $this->applicantRepository->setPresenter(ApplicantListPresenter::class);
            $this->applicantRepository->with(
                [
                    'application',
                    'application.first_priority_district',
                    'application.second_priority_district',
                    'citizenship_issued_district',
                    'verification',
                ]
            );

            $applicants = $this->applicantRepository->byVerifierId($user->id)->orderBy('id', 'asc')->paginate($perPage);
            $meta       = array_pop($applicants);
        } catch (ModelNotFoundException $exception) {
            $applicants = [];
        }
        $options = [
            'districts'         => $this->districtRepository->allDistrictForOptions()->values()->toArray(),
            'verificationTypes' => VerificationStatus::all(),
        ];
        $stats   = $this->verificationRepository->verificationStatsByUser((int) $user->id)->keyBy('verification_status')->toArray();

        return inertia(
            'ApplicationVerification/AssignedApplicationList',
            [
                'applicants' => $applicants,
                'stats'      => $stats,
                'pagination' => $meta['pagination'] ?? null,
                'options'    => $options,
                'queries'    => $queries ?: null,
            ]
        );
    }

    /**
     * @param int $applicantId
     *
     * @return RedirectResponse|Response|ResponseFactory
     */
    public function form($applicantId)
    {
        /** @var User $user */
        $user       = AuthHelper::currentUser();
        $assignment = $this->assignmentRepository->findWhere(['applicant_id' => $applicantId, 'verifier_id' => $user->id])->first();

        if ( $assignment ) {
            $this->applicantRepository->setPresenter(ApplicationVerificationPresenter::class);
            $applicant    = $this->applicantRepository->find((int) $applicantId);
            $verifiedData = $this->verificationRepository->findWhere(['applicant_id' => $applicantId, 'verifier_id' => $user->id])->first();

            return inertia(
                'ApplicationVerification/ApplicationVerificationForm',
                [
                    'applicant'    => $applicant,
                    'verifiedData' => data_get($verifiedData, null),
                    'options'      => [
                        'districts'         => $this->districtRepository->allDistrictForOptions()->values()->toArray(),
                        'genders'           => ApplicationConstants::genders(),
                        'verificationTypes' => VerificationStatus::all(),
                        'majorSubjects'     => ApplicationConstants::majorSubjects(),
                        'gradingSystems'    => ApplicationConstants::gradingSystems(),
                    ],
                ]
            );
        }

        return Redirect::route('admin.assigned-applications.index');
    }

    /**
     * @param ApplicationVerificationRequest $verificationRequest
     *
     * @return RedirectResponse
     */
    public function verify(ApplicationVerificationRequest $verificationRequest): RedirectResponse
    {
        $verificationRequest->set()->store();
        /** @var User $user */
        $user = AuthHelper::currentUser();

        if ( $verificationRequest->get('next') ) {
            $next = $this->assignmentRepository->doesntHave('verification')->where('verifier_id', $user->id)->min('applicant_id');
            if ( $next ) {
                session()->flash("success", "Previous application was successfully verified.");

                return redirect()->route('admin.assigned-applications.form', ['applicant_id' => $next], \Illuminate\Http\Response::HTTP_SEE_OTHER);
            }
            session()->flash("success", "All application were successfully verified.");

            return redirect()->route('admin.assigned-applications.index', [], \Illuminate\Http\Response::HTTP_SEE_OTHER);

        }
        session()->flash("success", "Application was successfully verified.");

        return redirect()->route('admin.assigned-applications.index', [], \Illuminate\Http\Response::HTTP_SEE_OTHER);
    }
}
