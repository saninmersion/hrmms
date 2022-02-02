<?php

namespace App\Domain\Applications\Repositories\Traits;

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\StatusTypes;
use Illuminate\Support\Collection;

/**
 * Trait GenderBasedStats
 * @package App\Domain\Applications\Repositories\Traits
 */
trait GenderBasedStats
{
    /**
     * @return Collection
     */
    public function getGenderBasedStats(): Collection
    {
        $builder = $this->getBuilder();

        $applicationTable = DBTables::APPLICATIONS;
        $applicantTable   = DBTables::AUTH_APPLICANTS;
        $draftStatus      = StatusTypes::APPLICATION_DRAFT;

        $subQuery = "select id,
                           {$this->selectApplicationPostSql()}
                    from {$applicationTable}
                    where status <> '{$draftStatus}'";

        $builder = $builder->joinSub($subQuery, 'x', "{$applicationTable}.id", '=', 'x.id');
        $builder = $builder->join($applicantTable, "{$applicationTable}.applicant_id", '=', "{$applicantTable}.id");
        $builder = $builder->groupByRaw("details ->> 'gender', application_post");
        $builder = $builder->selectRaw("details ->> 'gender' as gender, application_post, count(1) as applicant_count");
        $builder = $builder->orderByRaw("details ->> 'gender'");

        return $builder->get();
    }
}
