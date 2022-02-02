<?php

namespace App\Domain\Applicants\Repositories;

use App\Domain\Applicants\Models\ApplicantVerification;
use App\Infrastructure\Support\Repository;
use Illuminate\Support\Collection;

/**
 * Class ApplicantVerificationEloquentRepository
 * @package App\Domain\Applicants\Repositories
 */
class ApplicantVerificationEloquentRepository extends Repository implements ApplicantVerificationRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return ApplicantVerification::class;
    }

    /**
     * @return Collection
     */
    public function statsByUser()
    {
        $model = $this->getBuilder();

        $model = $model->selectRaw("verifier_id, to_char( created_at, 'MON DD') as date, verification_status, count(1) as verification_count");
        $model = $model->groupByRaw('1,2,3');
        $model = $model->orderByRaw('1,2,3');

        $data = $model->get();

        return $data->groupBy('verifier_id')->map(
            fn(Collection $stats) => $stats->groupBy('date')->map(
                fn(Collection $statsByDate) => $statsByDate->flatMap(
                    fn($statsByStatus) => [$statsByStatus->verification_status => $statsByStatus->verification_count]
                )
            )
        );
    }

    /**
     * @param int $verifierId
     *
     * @return Collection
     */
    public function verificationStatsByUser($verifierId)
    {
        $model = $this->getBuilder();
        $model = $model->where('verifier_id', $verifierId);
        $model = $model->selectRaw("verification_status, count(1) as count");
        $model = $model->groupByRaw('1');

        return $model->get();
    }
}
