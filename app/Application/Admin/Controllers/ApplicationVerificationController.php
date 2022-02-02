<?php

namespace App\Application\Admin\Controllers;

use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\Applicants\Criteria\ApplicationVerificationListCriteria;
use App\Domain\Applicants\Presenters\ApplicantListPresenter;
use App\Domain\Applicants\Presenters\ApplicationVerificationPresenter;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Repositories\ApplicantVerificationRepository;
use App\Domain\Applicants\Repositories\VerifierAssignmentRepository;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Constants\General;
use App\Infrastructure\Constants\VerificationStatus;
use App\Infrastructure\Support\Controller\AdminController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

/**
 * Class ApplicationVerificationController
 * @package App\Application\Admin\Controllers
 */
class ApplicationVerificationController extends AdminController
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
     * @var UserRepository
     */
    protected UserRepository $userRepository;
    /**
     * @var VerifierAssignmentRepository
     */
    protected VerifierAssignmentRepository $assignmentRepository;

    /**
     * ApplicationVerificationController constructor.
     *
     * @param ApplicantRepository             $applicantRepository
     * @param ApplicantVerificationRepository $verificationRepository
     * @param DistrictRepository              $districtRepository
     * @param VerifierAssignmentRepository    $assignmentRepository
     * @param UserRepository                  $userRepository
     */
    public function __construct(ApplicantRepository $applicantRepository, VerifierAssignmentRepository $assignmentRepository, ApplicantVerificationRepository $verificationRepository, DistrictRepository $districtRepository, UserRepository $userRepository)
    {
        $this->applicantRepository    = $applicantRepository;
        $this->districtRepository     = $districtRepository;
        $this->verificationRepository = $verificationRepository;
        $this->userRepository         = $userRepository;
        $this->assignmentRepository   = $assignmentRepository;
    }

    /**
     * @return Response|ResponseFactory
     */
    public function stats()
    {
        $stats               = $this->verificationRepository->statsByUser();
        $totalAssignedByUser = $this->assignmentRepository->getTotalAssignedByUser();
        $verifiers           = $this->userRepository->byRole(AuthRoles::VERIFIERS)->all();

        return inertia(
            'ApplicationVerification/VerifierStatList',
            [
                'stats'         => $stats->toArray(),
                'totalAssigned' => $totalAssignedByUser->toArray(),
                'verifiers'     => $verifiers,
                'downloads'     => [
                    'correction_needed_export_url' => route('admin.verifications.exports.correction_needed'),
                    'rejection_export_url'         => route('admin.verifications.exports.rejection'),
                ],
            ]
        );
    }

    /**
     * @param Request $request
     * @param int     $userId
     *
     * @return Response|ResponseFactory
     */
    public function listByUser(Request $request, $userId)
    {
        $queries = $request->all();
        $perPage = $request->input('per_page', General::PAGINATE_SM);

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

            $applicants = $this->applicantRepository->byVerifierId((int) $userId)->isVerified()->orderBy('id', 'asc')->paginate($perPage);
            $meta       = array_pop($applicants);
        } catch (ModelNotFoundException $exception) {
            $applicants = [];
        }
        $options = [
            'districts'         => $this->districtRepository->allDistrictForOptions()->values()->toArray(),
            'verificationTypes' => VerificationStatus::all(),
        ];

        return inertia(
            'ApplicationVerification/ApplicationVerificationByVerifierList',
            [
                'applicants' => $applicants,
                'pagination' => $meta['pagination'] ?? null,
                'options'    => $options,
                'queries'    => $queries ?: null,
            ]
        );
    }

    /**
     * @param int $verifierId
     * @param int $applicantId
     *
     * @return Response|ResponseFactory
     */
    public function show($verifierId, $applicantId)
    {
        $this->applicantRepository->setPresenter(ApplicationVerificationPresenter::class);
        $applicant    = $this->applicantRepository->find((int) $applicantId);
        $verifiedData = $this->verificationRepository->findWhere(['verifier_id' => (int) $verifierId, 'applicant_id' => (int) $applicantId])->first();

        return inertia(
            'ApplicationVerification/ApplicationVerificationDetail',
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
}
