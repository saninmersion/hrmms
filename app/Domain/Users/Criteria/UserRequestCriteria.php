<?php

namespace App\Domain\Users\Criteria;

use App\Infrastructure\Support\RequestCriteria;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRequestCriteria
 * @package App\Domain\Users\Criteria
 */
class UserRequestCriteria extends RequestCriteria
{
    /**
     * @param Builder     $model
     * @param string|null $search
     *
     * @return Builder
     */
    public function searchFilter($model, ?string $search = '')
    {
        if ( !$search ) {
            return $model;
        }

        return $model->where("name", "ilike", "%".$search."%");
    }

    /**
     * @param Builder     $model
     * @param string|null $status
     *
     * @return Builder
     */
    public function statusFilter($model, $status)
    {
        if ( !$status || $status === "all" ) {
            return $model;
        }

        return $model->where('status', $status);
    }

    /**
     * @param Builder     $model
     * @param string|null $role
     *
     * @return Builder
     */
    public function roleFilter($model, $role)
    {
        if ( !$role || $role === "all" ) {
            return $model;
        }

        return $model->where('role', $role);
    }

    /**
     * @param Builder $model
     * @param array   $filter
     *
     * @return Builder
     * @SuppressWarnings("unused")
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function postQuery($model, array $filter)
    {
        return $model->orderBy('name');
    }
}
