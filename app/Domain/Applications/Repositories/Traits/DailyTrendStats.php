<?php

namespace App\Domain\Applications\Repositories\Traits;

use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Constants\StatusTypes;
use Illuminate\Support\Collection;

/**
 * Trait DailyTrendStats
 * @package App\Domain\Applications\Repositories\Traits
 */
trait DailyTrendStats
{
    /**
     * @return Collection
     */
    public function getDailyStats(): Collection
    {
        $builder = $this->getBuilder();

        $applicationTable = DBTables::APPLICATIONS;
        $draftStatus      = StatusTypes::APPLICATION_DRAFT;

        $subQuery = "select id,
                           {$this->selectApplicationPostSql()}
                    from {$applicationTable}
                    where status <> '{$draftStatus}'";

        $builder = $builder->joinSub($subQuery, 'x', "{$applicationTable}.id", '=', 'x.id');
        $builder = $builder->groupByRaw("date(application_date), application_post");
        $builder = $builder->selectRaw(
            "date(application_date) as apply_date, application_post, count(1) as applicant_count"
        );
        $builder = $builder->orderByRaw("date(application_date)");

        return $builder->get();
    }
}
