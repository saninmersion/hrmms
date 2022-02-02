<?php

namespace App\Domain\Applicants\Repositories;

use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Support\Contracts\RepositoryInterface;

/**
 * Interface ApplicationListRepository
 * @package App\Domain\Applicants\Repositories
 */
interface ApplicationListRepository extends RepositoryInterface
{
    public function refreshView(): void;

    /**
     * @param string $status
     *
     * @return $this
     */
    public function byVerificationStatus(string $status);

    /**
     * @param int    $municipalityCode
     * @param string $role
     *
     * @return ApplicationListEloquentRepository
     */
    public function getShortlisted(int $municipalityCode, string $role): self;

    /**
     * @param int    $municipalityCode
     * @param string $role
     *
     * @return ApplicationListEloquentRepository
     */
    public function getOnlyShortlisted(int $municipalityCode, string $role): self;
}
