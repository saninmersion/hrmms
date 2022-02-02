<?php


namespace App\Domain\Enumerators\Repositories;


use App\Domain\Enumerators\Models\Enumerator;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\Builder;

class EnumeratorEloquentRepository extends Repository implements EnumeratorRepository
{

    public function model()
    {
        return Enumerator::class;
    }

    public function bySupervisor(int $supervisorId): EnumeratorRepository
    {
        /** @var Builder $model */
        $model       = $this->model;
        $this->model = $model->where('supervisor_id', $supervisorId);

        return $this;
    }
}
