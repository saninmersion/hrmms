<?php

namespace App\Domain\Applicants\Repositories;

use App\Domain\Applicants\Models\ApplicantExport;
use App\Infrastructure\Support\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface ApplicantExportRepository
 * @package App\Domain\Applicants\Repositories
 */
interface ApplicantExportRepository extends RepositoryInterface
{
    /**
     * @return ApplicantExport|array
     * @throws ModelNotFoundException
     */
    public function getLatestExportedFile();
}
