<?php

namespace App\Domain\Applicants\Repositories;

use App\Domain\Applicants\DTO\ApplicantLoginAttemptDTO;
use App\Domain\Applicants\Models\Applicant;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ApplicantEloquentRepository
 * @package App\Domain\Applicants\Repositories
 */
class ApplicantEloquentRepository extends Repository implements ApplicantRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Applicant::class;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function byStatus(string $status): self
    {
        /** @var Builder $model */
        $model = $this->model;

        $this->model = $model->whereHas(
            "application",
            function (Builder $query) use ($status) {
                $query->where('status', $status);
            }
        );

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function byRole(string $role): self
    {
        /** @var Builder $model */
        $model = $this->model;

        $this->model = $model->whereHas(
            "application",
            function (Builder $query) use ($role) {
                $query->where("for_$role", true);
            }
        );

        return $this;
    }

    /**
     * @param int $verifierId
     *
     * @return $this
     */
    public function byVerifierId(int $verifierId): self
    {
        $this->model = $this->getBuilder()->where(
            function ($query) use ($verifierId) {
                /** @var Builder $query */
                $query->whereHas(
                    "verification_assignment",
                    function (Builder $query) use ($verifierId) {
                        $query->where('verifier_id', "=", $verifierId);
                    }
                );
            }
        );

        return $this;
    }

    /**
     * @param ApplicantLoginAttemptDTO $data
     *
     * @return Applicant|array
     * @throws ModelNotFoundException
     */
    public function checkApplicantForLogin(ApplicantLoginAttemptDTO $data)
    {
        /** @var Builder $model */
        $model = $this->model;

        $model = $model->where(
            function ($query) use ($data) {
                /** @var Builder $query */
                $query->where('dob_bs', $data->dob);
                $query->where('mobile_number', $data->mobileNumber);
            }
        );

        $applicants = $model->get();

        if ( $applicants->isEmpty() ) {
            throw new ModelNotFoundException();
        }

        return $this->parserResult($applicants->first());
    }

    /**
     * @return $this
     */
    public function whereUnassignedToVerifiers(): self
    {
        /** @var Builder $model */
        $model = $this->model;

        $this->model = $model->doesntHave('verification_assignment');

        return $this;
    }

    /**
     * @return ApplicantRepository
     */
    public function isVerified(): ApplicantRepository
    {
        $this->model = $this->getBuilder()->has('verification');

        return $this;
    }

    /**
     * @param bool $status
     *
     * @return ApplicantRepository
     */
    public function byOfflineStatus(bool $status): ApplicantRepository
    {
        $this->model = $this->getBuilder()->where(
            function ($query) use ($status) {
                /** @var Builder $query */
                $query->whereHas(
                    "application",
                    function (Builder $query) use ($status) {
                        $query->where('is_offline', "=", $status);
                    }
                );
            }
        );

        return $this;
    }

    /**
     * @param int $operatorId
     *
     * @return ApplicantRepository
     */
    public function byOperator(int $operatorId): ApplicantRepository
    {
        $this->model = $this->getBuilder()->where(
            function ($query) use ($operatorId) {
                /** @var Builder $query */
                $query->whereHas(
                    "application",
                    function (Builder $query) use ($operatorId) {
                        $query->where('entered_by_id', "=", $operatorId);
                    }
                );
            }
        );

        return $this;
    }

    /**
     * @param string $citizenship
     * @param string $mobile
     *
     * @return ApplicantRepository
     */
    public function findWhereCitizenshipOrPhone(string $citizenship, string $mobile): ApplicantRepository
    {
        $this->model = $this->getBuilder()->where(
            function ($query) use ($citizenship, $mobile) {
                /** @var Builder $query */
                $query->orWhere('citizenship_number', $citizenship);
                $query->orWhere('mobile_number', $mobile);
            }
        );

        return $this;
    }
}
