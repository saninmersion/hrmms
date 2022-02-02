<?php

namespace App\Domain\Applicants\Actions;

use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Repositories\VerifierAssignmentRepository;
use App\Domain\Users\Models\User;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\StatusTypes;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Class AssignApplications
 * @package App\Domain\Applicants\Actions
 */
class AssignApplications
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;
    /**
     * @var VerifierAssignmentRepository
     */
    protected VerifierAssignmentRepository $verifierAssignmentRepository;
    /**
     * @var ApplicantRepository
     */
    protected ApplicantRepository $applicantRepository;
    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $dbManager;

    /**
     * AssignApplications constructor.
     *
     * @param UserRepository               $userRepo
     * @param VerifierAssignmentRepository $vaRepo
     * @param ApplicantRepository          $aRepository
     */
    public function __construct(UserRepository $userRepo, VerifierAssignmentRepository $vaRepo, ApplicantRepository $aRepository, DatabaseManager $dbManager)
    {
        $this->verifierAssignmentRepository = $vaRepo;
        $this->userRepository               = $userRepo;
        $this->applicantRepository          = $aRepository;
        $this->dbManager                    = $dbManager;
    }

    /**Assign applicants to verifiers
     *
     * @param User $verifier
     * @param int  $numberOfAssignments
     *
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function assignApplicationsToVerifiers(User $verifier, int $numberOfAssignments): void
    {
        /** @var Collection $applicants */
        $applicants = $this->applicantRepository->byStatus(StatusTypes::APPLICATION_SUBMITTED)->byRole(ApplicationType::ENUMERATOR)->whereUnassignedToVerifiers()->orderBy('id', 'asc')->take($numberOfAssignments)->get();

        $this->dbManager->beginTransaction();
        try {
            foreach ($applicants as $applicant) {
                $this->verifierAssignmentRepository->create(
                    [
                        'verifier_id'  => $verifier->id,
                        'applicant_id' => $applicant->id,
                    ]
                );
            }

            $this->dbManager->commit();
        } catch (Exception $exception) {
            $this->dbManager->rollBack();
            throw $exception;
        }
    }
}
