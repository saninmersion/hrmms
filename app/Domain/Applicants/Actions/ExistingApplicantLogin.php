<?php

namespace App\Domain\Applicants\Actions;

use App\Domain\Applicants\DTO\ApplicantLoginAttemptDTO;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Support\Exceptions\LoginFailedException;
use App\Infrastructure\Constants\Guard;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ExistingApplicantLogin
 * @package App\Domain\Applicants\Actions
 */
class ExistingApplicantLogin
{
    /**
     * @var ApplicantRepository
     */
    protected ApplicantRepository $repository;

    /**
     * ExistingApplicantLogin constructor.
     *
     * @param ApplicantRepository $repository
     */
    public function __construct(ApplicantRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ApplicantLoginAttemptDTO $data
     *
     * @return void
     * @throws LoginFailedException
     */
    public function attempt(ApplicantLoginAttemptDTO $data): void
    {
        try {
            /** @var Applicant $applicant */
            $applicant = $this->repository->checkApplicantForLogin($data);
        } catch (ModelNotFoundException $exception) {
            throw new LoginFailedException('not-found');
        }

        $this->login($applicant);
    }

    /**
     * @param Applicant $applicant
     */
    public function login(Applicant $applicant): void
    {
        auth()->guard(Guard::APPLICANT)->login($applicant);
    }
}
