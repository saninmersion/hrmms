<?php

namespace App\Domain\Applicants\Criteria;

use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\RequestCriteria;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Class ShortlistedApplicantListCriteria
 * @package App\Domain\Applicants\Criteria
 */
class ShortlistedApplicantListCriteria extends RequestCriteria
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
            $model = $model->where(
                function ($query) use ($municipality) {
                    /** @var Builder $query */
                    $query->orWhere('first_priority_municipality_code', $municipality);
                    $query->orWhere('second_priority_municipality_code', $municipality);
                }
            );
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
        $rolesArray = [
            'supervisor' => [ApplicationType::SUPERVISOR, ApplicationType::ENUMERATOR_SUPERVISOR],
            'enumerator' => [ApplicationType::ENUMERATOR, ApplicationType::ENUMERATOR_SUPERVISOR],
        ];

        if ( Arr::has($rolesArray, $role) ) {
            $model = $model->whereIn('application_for', Arr::get($rolesArray, $role));
        }

        return $model;
    }

    /**
     * @param Builder     $model
     * @param string|null $district
     *
     * @return Builder
     */
    public function districtFilter($model, $district)
    {
        if ( !$district || $district === "all" ) {
            return $model;
        }

        return $model->where(
            function ($query) use ($district) {
                $query->where(
                    function ($q) use ($district) {
                        $q->orWhere('first_priority_district_code', (int) $district);
                        $q->orWhere('second_priority_district_code', (int) $district);
                    }
                );
            }
        );
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

        return $model->where(
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
                $q->orWhereRaw(DB::raw("{$sql} ilike '%{$search}%'"));
                $q->orWhereRaw(DB::raw("{$sqlForEnglish} ilike '%{$search}%'"));
            }
        );
    }

    /**
     * @param Builder $model
     * @param array   $filter
     *
     * @return Builder
     * @SuppressWarnings("unused")
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function postQuery(Builder $model, array $filter)
    {
        $model = $model->where('score', '>', 0);

        $model = $model->orderBy('score', 'desc')->orderBy('application_date', 'asc');

        /** @var User $currentUser */
        $currentUser = AuthHelper::currentUser();

        $district = $currentUser->role === AuthRoles::DISTRICT_STAFFS ? $currentUser->district_code : $filter['district'];

        return $this->districtFilter($model, (string) $district);
    }
}
