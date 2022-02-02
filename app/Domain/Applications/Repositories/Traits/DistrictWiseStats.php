<?php

namespace App\Domain\Applications\Repositories\Traits;

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\StatusTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Trait DistrictWiseStats
 * @package App\Domain\Applications\Repositories\Traits
 */
trait DistrictWiseStats
{
    /**
     * @param string $priority
     *
     * @return Collection
     */
    public function getDistrictsStats(string $priority = 'first'): Collection
    {
        /** @var Builder $model */
        $model = $this->model;

        $draftStatus = StatusTypes::APPLICATION_DRAFT;

        $selectSql = "(locations -> '{$priority}' ->> 'district')::int as district,
                    {$this->selectApplicationPostSql()},
                    count(1) as applicant_count";

        $model = $model->selectRaw($selectSql);
        $model = $model->where('status', '<>', $draftStatus);
        $model = $model->groupByRaw("1,2");
        $model = $model->orderByRaw("1,2");

        return $model->get();
    }

    /**
     * @return Collection
     */
    public function getGenderBasedStatsByDistrict(): Collection
    {
        $applicationTable = DBTables::APPLICATIONS;
        $applicantTable   = DBTables::AUTH_APPLICANTS;
        $draftStatus      = StatusTypes::APPLICATION_DRAFT;

        $sql = "
             select x.district
                  , aa.details ->> 'gender' as gender
                  , x.application_post
                  , count(1)                as applicant_count
             from (
                      select applicant_id
                           , locations -> 'first' ->> 'district' as district
                           , {$this->selectApplicationPostSql()}
                      from {$applicationTable}
                      where locations -> 'first' ->> 'district' is not null
                        and status <> '{$draftStatus}'

                      union all

                      select applicant_id
                           , locations -> 'second' ->> 'district' as district
                           , {$this->selectApplicationPostSql()}
                      from {$applicationTable}
                      where locations -> 'second' ->> 'district' is not null
                        and status <> '{$draftStatus}'
                  ) as x
                      left join {$applicantTable} as aa on aa.id = x.applicant_id
             group by 1, 2, 3
             order by 1
        ";

        return collect(DB::select($sql));
    }

    /**
     * @return Collection
     */
    public function getOverAllStatsByDistrict(): Collection
    {
        $applicationTable = DBTables::APPLICATIONS;

        $sql = "
             select x.district
                  , x.status
                  , x.application_post
                  , count(1) as applicant_count
             from (
                      select locations -> 'first' ->> 'district' as district
                           , status
                           , {$this->selectApplicationPostSql()}
                      from {$applicationTable}
                      where locations -> 'first' ->> 'district' is not null

                      union all

                      select locations -> 'second' ->> 'district' as district
                           , status
                           , {$this->selectApplicationPostSql()}
                      from {$applicationTable}
                      where locations -> 'second' ->> 'district' is not null
                  ) as x
             group by 1, 2, 3
             order by 1
        ";

        return collect(DB::select($sql));
    }
}
