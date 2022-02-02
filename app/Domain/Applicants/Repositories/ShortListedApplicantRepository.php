<?php

namespace App\Domain\Applicants\Repositories;

use App\Infrastructure\Support\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface ShortListedApplicantRepository
 *
 * @package App\Domain\Applicants\Repositories
 */
interface ShortListedApplicantRepository extends RepositoryInterface
{
    /**
     * @param bool $status
     *
     * @return $this
     */
    public function byShortlistedStatus(bool $status): self;

    /**
     * @param int $municipalityCode
     *
     * @return $this
     */
    public function byMunicipality(int $municipalityCode): self;

    /**
     * @return Collection
     */
    public function getHiredAndRejectedCountByDistrictAndRole(): Collection;

    /**
     * @param string $role
     *
     * @return $this
     */
    public function byRole(string $role): self;

    /**
     * @param string $status
     *
     * @return $this
     */
    public function byHiringStatus(string $status): self;

    /**
     * @param string $mobileNumber
     *
     * @return $this
     */
    public function isShortlistedByMobileNumber(string $mobileNumber);

    /**
     * @param string $submissionNumber
     *
     * @return $this
     */
    public function isShortlistedBySubmissionNumber(string $submissionNumber);
}
