<?php

namespace App\Domain\Applicants\Repositories;

use App\Domain\Applicants\DTO\ApplicantLoginAttemptDTO;
use App\Domain\Applicants\Models\Applicant;
use App\Domain\Applicants\Models\VerifierAssignment;
use App\Infrastructure\Support\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface ApplicantRepository
 * @package App\Domain\Applicants\Repositories
 */
interface ApplicantRepository extends RepositoryInterface
{
    /**
     * @param ApplicantLoginAttemptDTO $data
     *
     * @return Applicant|array
     * @throws ModelNotFoundException
     */
    public function checkApplicantForLogin(ApplicantLoginAttemptDTO $data);

    /**
     * @param int $verifierId
     *
     * @return $this
     */
    public function byVerifierId(int $verifierId): self;

    /**
     *
     * @return $this
     */
    public function isVerified(): self;

    /**
     * @return $this
     */
    public function whereUnassignedToVerifiers(): self;

    /**
     * @param string $status
     *
     * @return $this
     */
    public function byStatus(string $status): self;

    /**
     * @param string $role
     *
     * @return $this
     */
    public function byRole(string $role): self;

    /**
     * @param bool $status
     *
     * @return ApplicantRepository
     */
    public function byOfflineStatus(bool $status): ApplicantRepository;

    /**
     * @param int $operatorId
     *
     * @return ApplicantRepository
     */
    public function byOperator(int $operatorId): ApplicantRepository;

    /**
     * @param string $citizenship
     * @param string $mobile
     *
     * @return ApplicantRepository
     */
    public function findWhereCitizenshipOrPhone(string $citizenship, string $mobile): ApplicantRepository;
}
