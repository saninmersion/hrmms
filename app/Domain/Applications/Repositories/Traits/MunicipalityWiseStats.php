<?php

namespace App\Domain\Applications\Repositories\Traits;

use App\Infrastructure\Constants\StatusTypes;
use Illuminate\Support\Collection;

/**
 * Trait MunicipalityWiseStats
 * @package App\Domain\Applications\Repositories\Traits
 */
trait MunicipalityWiseStats
{
    /**
     * @param string $priority
     *
     * @return Collection
     */
    public function getMunicipalityStats(string $priority = 'first'): Collection
    {
        $builder     = $this->getBuilder();
        $draftStatus = StatusTypes::APPLICATION_DRAFT;

        $selectSql = "
            (locations -> '{$priority}' ->> 'district')::int as district
            , (locations -> '{$priority}' ->> 'municipality')::int as municipality
            , {$this->selectApplicationPostSql()}
            , count(1) as applicant_count
       ";

        $builder = $builder->selectRaw($selectSql);
        $builder = $builder->where('status', '<>', $draftStatus);
        $builder = $builder->whereRaw("(locations -> '{$priority}' ->> 'municipality') is not null");
        $builder = $builder->groupByRaw("1,2,3");
        $builder = $builder->orderByRaw("1");

        return $builder->get();
    }
}
