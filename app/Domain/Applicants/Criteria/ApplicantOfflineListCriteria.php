<?php

namespace App\Domain\Applicants\Criteria;

use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\RequestCriteria;
use App\Infrastructure\Support\Unicode\NepaliNumber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ApplicantOfflineListCriteria
 * @package App\Domain\Users\Criteria
 */
class ApplicantOfflineListCriteria extends RequestCriteria
{
    /**
     * @param Builder     $model
     * @param string|null $status
     *
     * @return Builder
     */
    public function applicationStatusFilter($model, $status)
    {
        if ( !$status || $status === "all" ) {
            return $model;
        }

        return $model->whereHas(
            "application",
            function (Builder $query) use ($status) {
                $query->where('status', $status);
            }
        );

    }

    /**
     * @param Builder     $model
     * @param string|null $gender
     *
     * @return Builder
     */
    public function genderFilter($model, $gender)
    {
        if ( !$gender || $gender === "all" ) {
            return $model;
        }

        return $model->where('details->gender', $gender);
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
                $sql           = "concat(
               coalesce(details -> 'name_in_nepali' ->> 'first_name', ''),
               coalesce(details -> 'name_in_nepali' ->> 'middle_name', ''),
               coalesce(details -> 'name_in_nepali' ->> 'last_name', '')
           )";
                $sqlForEnglish = "concat(
               coalesce(details -> 'name_in_english' ->> 'first_name', ''),
               coalesce(details -> 'name_in_english' ->> 'middle_name', ''),
               coalesce(details -> 'name_in_english' ->> 'last_name', '')
           )";
                /** @var Builder $q */
                $q->orWhereRaw(DB::raw("{$sql} ilike '%{$search}%'"));
                $q->orWhereRaw(DB::raw("{$sqlForEnglish} ilike '%{$search}%'"));
                $submissionNumber = $this->parseForSubmissionNumber($search);
                if ( $submissionNumber ) {
                    $q->orWhereHas(
                        'application',
                        function ($q) use ($submissionNumber) {
                            $q->where('id', $submissionNumber);
                        }
                    );
                }
                $q->orWhere("citizenship_number", "ilike", "%{$search}%");

                $phoneNumber = NepaliNumber::nepaliToEnglish($search);
                $q->orWhere("mobile_number", $phoneNumber);

                $dob = str_replace('/', '-', NepaliNumber::nepaliToEnglish($search));
                $q->orWhere("dob_bs", $dob);
                $q->orWhere("details->dob_ad", $dob);
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

        return $model->whereHas(
            "application",
            function ($query) use ($role) {
                if ( in_array($role, [ApplicationType::ENUMERATOR, ApplicationType::ENUMERATOR_SUPERVISOR]) ) {
                    $query->where('for_enumerator', true);
                }
                if ( in_array($role, [ApplicationType::SUPERVISOR, ApplicationType::ENUMERATOR_SUPERVISOR]) ) {
                    $query->where('for_supervisor', true);
                }
            }
        );
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

        return $model->whereHas(
            "application",
            function ($query) use ($district) {
                $query->where(
                    function ($q) use ($district) {
                        $q->orWhere('locations->first->district', (int) $district);
                        $q->orWhere('locations->second->district', (int) $district);
                    }
                );
            }
        );
    }
}
