<?php

namespace App\Domain\Applicants\Repositories;

use App\Domain\Applicants\Models\ApplicationListView;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\StatusTypes;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ApplicationListEloquentRepository
 * @package App\Domain\Applicants\Repositories
 */
class ApplicationListEloquentRepository extends Repository implements ApplicationListRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return ApplicationListView::class;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function byVerificationStatus(string $status)
    {
        $this->model = $this->getBuilder()->whereHas(
            'verification',
            function (Builder $query) use ($status) {
                $query->where('verification_status', $status);
            }
        );

        return $this;
    }

    /**
     * @param int    $municipalityCode
     * @param string $role
     *
     * @return ApplicationListEloquentRepository
     */
    public function getShortlisted(int $municipalityCode, string $role): self
    {
        $model = $this->getBuilder();

        $applicationListTable = DBTables::VIEW_APPLICATIONS;
        $model                = $model->crossJoin(DB::raw("lateral calculate_score({$applicationListTable}, role := '{$role}') as score"));
        $model                = $model->where('status', '=', StatusTypes::APPLICATION_SUBMITTED);
        $model                = $model->with(
            [
                'shortlist' => function ($query) use ($municipalityCode) {
                    /** @var Builder $query */
                    $query->where('municipality_code', $municipalityCode);
                },
            ]
        );

        $this->model = $model;

        return $this;
    }

    /**
     * @param int    $municipalityCode
     * @param string $role
     *
     * @return $this
     */
    public function getOnlyShortlisted(int $municipalityCode, string $role): self
    {
        $model = $this->getBuilder();

        $applicationListTable = DBTables::VIEW_APPLICATIONS;
        $model                = $model->crossJoin(DB::raw("lateral calculate_score({$applicationListTable}, role := '{$role}') as score"));
        $model                = $model->has('shortlist');
        $model                = $model->with(
            [
                'shortlist' => function ($query) use ($municipalityCode, $role) {
                    /** @var Builder $query */
                    $query->where('municipality_code', $municipalityCode);
                    $query->where('role', $role);
                },
            ]
        );

        $this->model = $model;

        return $this;
    }

    public function refreshView(): void
    {
        $viewName = DBTables::VIEW_APPLICATIONS;

        DB::statement("REFRESH MATERIALIZED VIEW CONCURRENTLY {$viewName};");
    }
}
