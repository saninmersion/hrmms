<?php

namespace App\Domain\Applicants\Criteria;

use App\Infrastructure\Support\RequestCriteria;
use App\Infrastructure\Support\Unicode\NepaliNumber;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ApplicationVerificationListCriteria
 * @package App\Domain\Users\Criteria
 */
class ApplicationVerificationListCriteria extends RequestCriteria
{
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

        if ( $status === 'not_verified' ) {
            return $model->whereDoesntHave("verification");
        }

        return $model->whereHas(
            "verification",
            function (Builder $query) use ($status) {
                $query->where('verification_status', $status);
            }
        );

    }

    /**
     * @param Builder     $model
     * @param string|null $search
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
                $q->orWhere("citizenship_number", "ilike", "%{$search}%");

                $dob = str_replace('/', '-', NepaliNumber::nepaliToEnglish($search));
                $q->orWhere("dob_bs", $dob);

                $submissionNumber = $this->parseForSubmissionNumber($search);
                if ( $submissionNumber ) {
                    $q->orWhereHas(
                        'application',
                        function ($q) use ($submissionNumber) {
                            $q->where('id', $submissionNumber);
                        }
                    );
                }
            }
        );
    }

    /**
     * @param string $search
     *
     * @return int|null
     */
    public function parseForSubmissionNumber(string $search): ?int
    {
        if ( !preg_match("/^NPHC-(\d{7})$/", $search, $matches) ) {
            return null;
        }

        return (int) $matches[1];
    }
}
