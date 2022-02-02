<?php

namespace App\Domain\Applicants\Criteria;

use App\Infrastructure\Support\RequestCriteria;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ShortlistedApplicantDistrictListCriteria
 * @package App\Domain\Applicants\Criteria
 */
class ShortlistedApplicantDistrictListCriteria extends RequestCriteria
{
    /**
     * @param Builder  $model
     * @param int|null $municipality
     *
     * @return Builder
     */
    public function municipalityFilter($model, $municipality)
    {
        if ( $municipality ) {
            $model = $model->where('municipality_code', $municipality);
        }

        return $model;
    }

    /**
     * @param Builder $model
     * @param string  $role
     *
     * @return Builder
     */
    public function roleFilter($model, $role)
    {
        if ( !$role ) {
            return $model;
        }

        return $model->where('role', $role);
    }

    /**
     * @param Builder $model
     * @param string  $search
     *
     * @return Builder
     */
    public function searchFilter($model, $search)
    {
        if ( !$search ) {
            return $model;
        }

        return $model->whereHas(
            'applicationView',
            function ($q) use ($search) {
                $sql           = "concat(
               coalesce(name_in_nepali ->> 'first_name', ' '),
               coalesce(name_in_nepali ->> 'middle_name', ' '),
               coalesce(name_in_nepali ->> 'last_name', ' ')
           )";
                $sqlForEnglish = "concat(
               coalesce(name_in_english ->> 'first_name', ' '),
               coalesce(name_in_english ->> 'middle_name', ' '),
               coalesce(name_in_english ->> 'last_name', ' ')
           )";

                /** @var Builder $q */
                $q->whereRaw(DB::raw("{$sql} ilike '%{$search}%'"));
                $q->orWhereRaw(DB::raw("{$sqlForEnglish} ilike '%{$search}%'"));
            }
        );
    }
}
