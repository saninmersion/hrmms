<?php

namespace App\Domain\Applicants\Repositories;

use App\Domain\Applicants\Models\DownloadLog;
use App\Infrastructure\Support\Repository;

/**
 * Class DownloadLogEloquentRepository
 * @package App\Domain\Applicants\Repositories
 */
class DownloadLogEloquentRepository extends Repository implements DownloadLogRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return DownloadLog::class;
    }
}
