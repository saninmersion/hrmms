<?php

namespace App\Domain\Applicants\Repositories;

use App\Domain\Applicants\Models\ShortListedApplicant;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\Repository;
use App\Infrastructure\Support\Unicode\NepaliNumber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class ShortListedApplicantEloquentRepository
 *
 * @package App\Domain\Applicants\Repositories
 */
class ShortListedApplicantEloquentRepository extends Repository implements ShortListedApplicantRepository
{
    /**
     * @return string|void
     */
    public function model()
    {
        return ShortListedApplicant::class;
    }

    /**
     * @param int $municipalityCode
     *
     * @return $this
     */
    public function byMunicipality(int $municipalityCode): ShortListedApplicantEloquentRepository
    {
        $this->model = $this->getBuilder()->where('municipality_code', $municipalityCode);

        return $this;
    }

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function byShortlistedStatus(bool $status): ShortListedApplicantEloquentRepository
    {
        $this->model = $this->getBuilder()->where('is_shortlisted', $status);

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function byRole(string $role): self
    {
        $this->model = $this->getBuilder()->where('role', $role);

        return $this;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function byHiringStatus(string $status): self
    {
        $this->model = $this->getBuilder()->where('hiring_status', $status);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getHiredAndRejectedCountByDistrictAndRole(): Collection
    {
        $tblShortlisted    = DBTables::SHORTLISTED;
        $tblMunicipalities = DBTables::MUNICIPALITIES;
        $sql               = "
        select tm.district_code,ss.role, ss.hiring_status, count(tm.district_code)
        from {$tblShortlisted} ss
            inner join {$tblMunicipalities} tm
                on tm.code = ss.municipality_code
        where ss.hiring_status is not null
        group by 1,2,3
        order by 1
        ";

        return collect(DB::select($sql));
    }

    /**
     * @param string $mobileNumber
     */
    public function isShortlistedByMobileNumber(string $mobileNumber)
    {
        $this->model = $this->getBuilder()->whereHas(
            'applicant', function ($q) use ($mobileNumber) {
                $q->where('mobile_number', NepaliNumber::nepaliToEnglish($mobileNumber));
            }
        );

        return $this;
    }

    /**
     * @param string $submissionNumber
     */
    public function isShortlistedBySubmissionNumber(string $submissionNumber)
    {
        $submissionNumber = $this->parseForSubmissionNumber($submissionNumber);

        $this->model = $this->getBuilder()->whereHas(
            'application', function ($q) use ($submissionNumber) {
                $q->where('id', $submissionNumber);
            }
        );

        return $this;
    }


    /**
     * @param string $search
     *
     * @return int|null
     */
    protected function parseForSubmissionNumber(string $search): ?int
    {
        if ( !preg_match("/^NSCA-(\d{7})$/", $search, $matches) ) {
            return null;
        }

        return (int) $matches[1];
    }
}
