<?php

namespace App\Domain\Applicants\Repositories;

use App\Infrastructure\Support\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface ApplicantVerificationRepository
 * @package App\Domain\Applicants\Repositories
 */
interface ApplicantVerificationRepository extends RepositoryInterface
{
    /**
     * @return Collection
     */
    public function statsByUser();

    /**
     * @param int $verifierId
     *
     * @return Collection
     */
    public function verificationStatsByUser($verifierId);
}
