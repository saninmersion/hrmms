<?php

namespace App\Domain\HouseDailyReports\Criteria;

use App\Infrastructure\Support\RequestCriteria;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class DailyReportListCriteria
 * @package App\Domain\HouseDailyReports\Criteria
 */
class HouseDailyReportListCriteria extends RequestCriteria
{
    /**
     * @param Builder     $model
     * @param string|null $type
     *
     * @return Builder
     */
    public function districtCodeFilter($model, $type)
    {
        if ( !$type || $type === "all" ) {
            return $model;
        }

        return $model->where("district_code", "=", $type);
    }

    /**
     * @param Builder     $model
     * @param string|null $type
     *
     * @return Builder
     */
    public function censusOfficeIdFilter($model, $type)
    {
        if ( !$type || $type === "all" ) {
            return $model;
        }

        return $model->where("census_office_id", "=", $type);
    }

    /**
     * @param Builder     $model
     * @param string|null $supervisor
     *
     * @return Builder
     */
    public function supervisorFilter($model, $supervisor)
    {
        if ( !$supervisor ) {
            return $model;
        }

        return $model->whereHas(
            'supervisor',
            function ($query) use ($supervisor) {
                return $query->where('name', 'ILIKE', "%{$supervisor}%");
            }
        );
    }
}
