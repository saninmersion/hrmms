<?php

namespace App\Domain\Applications\Repositories\Traits;

use App\Infrastructure\Constants\DBTables;
use Illuminate\Support\Collection;

/**
 * Trait OverallStats
 * @package App\Domain\Applications\Repositories\Traits
 */
trait OverallStats
{
    /**
     * @return Collection
     */
    public function getOverAllStats(): Collection
    {
        $builder = $this->getBuilder();

        $applicationTable = DBTables::APPLICATIONS;

        $subQuery = "select id, {$this->selectApplicationPostSql()}
                    from {$applicationTable}";

        $builder = $builder->joinSub($subQuery, 'x', "{$applicationTable}.id", '=', 'x.id');
        $builder = $builder->groupBy('x.application_post', 'status');
        $builder = $builder->selectRaw("x.application_post, status, count(1) as applicant_count");

        return $builder->get();
    }
}
