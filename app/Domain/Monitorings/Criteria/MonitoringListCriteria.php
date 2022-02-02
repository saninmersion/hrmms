<?php


namespace App\Domain\Monitorings\Criteria;


use App\Infrastructure\Support\RequestCriteria;
use Illuminate\Database\Eloquent\Builder;

class MonitoringListCriteria extends RequestCriteria
{

    /**
     * @param Builder     $model
     * @param string|null $type
     *
     * @return Builder
     */
    public function formTypeFilter($model, $type)
    {
        if ( !$type || $type === "all" ) {
            return $model;
        }

        return $model->where("monitoring_form", "=", $type);
    }

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
    public function municipalityCodeFilter($model, $type)
    {
        if ( !$type || $type === "all" ) {
            return $model;
        }

        return $model->where("municipality_code", "=", $type);
    }
}
