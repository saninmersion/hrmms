<?php

namespace App\Domain\Applicants\Repositories;

use App\Domain\Applicants\Models\ApplicantExport;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ApplicantExportEloquentRepository
 * @package App\Domain\Applicants\Repositories
 */
class ApplicantExportEloquentRepository extends Repository implements ApplicantExportRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return ApplicantExport::class;
    }

    /**
     * @return ApplicantExport|array
     * @throws ModelNotFoundException
     */
    public function getLatestExportedFile()
    {
        /** @var Builder $model */
        $model = $this->getBuilder();

        $exportedList = $model->where("metadata->status", true)->whereNotNull('exported_at')->latest()->firstOrFail();

        return $this->parserResult($exportedList);
    }
}
