<?php

namespace App\Domain\Users\Repositories;

use App\Domain\Users\Models\User;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserEloquentRepository
 * @package App\Domain\Users\Repositories
 */
class UserEloquentRepository extends Repository implements UserRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param string $roleName
     *
     * @return $this
     */
    public function byRole(string $roleName): self
    {
        /** @var Builder $model */
        $model       = $this->model;
        $this->model = $model->where('role', $roleName);

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return $this|UserRepository
     */
    public function exceptRoles(array $roles): UserRepository
    {
        /** @var Builder $model */
        $model       = $this->model;
        $this->model = $model->whereNotIn('role', $roles);

        return $this;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function byStatus(string $status): self
    {
        /** @var Builder $model */
        $model       = $this->model;
        $this->model = $model->where('status', $status);

        return $this;
    }

    /**
     * @param array $status
     *
     * @return $this
     */
    public function exceptStatus(array $status): self
    {
        /** @var Builder $model */
        $model       = $this->getBuilder();
        $this->model = $model->orWhereNotIn('status', $status);

        return $this;
    }
}
