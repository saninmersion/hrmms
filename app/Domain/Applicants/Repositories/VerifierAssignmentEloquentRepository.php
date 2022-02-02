<?php


namespace App\Domain\Applicants\Repositories;


use App\Domain\Applicants\Models\VerifierAssignment;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class VerifierAssignmentEloquentRepository
 * @package App\Domain\Applicants\Repositories
 */
class VerifierAssignmentEloquentRepository extends Repository implements VerifierAssignmentRepository
{

    /**
     * @inheritDoc
     */
    public function model()
    {
        return VerifierAssignment::class;
    }

    /**
     * @param int $verifierId
     *
     * @return mixed
     */
    public function findByVerifierId(int $verifierId)
    {
        /** @var Builder $model */
        $model = $this->getBuilder();

        $result = $model->where('verifier_id', '=', $verifierId)->firstOrFail();

        return $this->parserResult($result);
    }

    public function getTotalAssignedByUser()
    {
        $model = $this->getBuilder();

        $model = $model->selectRaw("verifier_id, count(1) as verification_count");
        $model = $model->groupByRaw('1');
        $model = $model->orderByRaw('1');

        return $model->get();
    }
}
