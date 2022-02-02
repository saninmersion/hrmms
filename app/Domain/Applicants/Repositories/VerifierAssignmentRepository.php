<?php


namespace App\Domain\Applicants\Repositories;

use App\Infrastructure\Support\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface VerifierAssignmentRepository
 * @package App\Domain\Applicants\Repositories
 */
interface VerifierAssignmentRepository extends RepositoryInterFace
{
    /**
     * @param int $verifierId
     *
     * @return mixed
     */
    public function findByVerifierId(int $verifierId);

    /**
     * @return Collection
     */
    public function getTotalAssignedByUser();
}
