<?php

namespace App\Domain\Applications\Repositories;

use App\Domain\Applications\Models\Application;
use App\Domain\Applications\Repositories\Traits\DailyTrendStats;
use App\Domain\Applications\Repositories\Traits\DistrictWiseStats;
use App\Domain\Applications\Repositories\Traits\GenderBasedStats;
use App\Domain\Applications\Repositories\Traits\MunicipalityWiseStats;
use App\Domain\Applications\Repositories\Traits\OverallStats;
use App\Infrastructure\Constants\ApplicationType;
use App\Infrastructure\Support\Repository;

/**
 * Class ApplicationEloquentRepository
 * @package App\Domain\Applications\Repositories
 */
class ApplicationEloquentRepository extends Repository implements ApplicationRepository
{
    use OverallStats;
    use GenderBasedStats;
    use DailyTrendStats;
    use DistrictWiseStats;
    use MunicipalityWiseStats;

    /**
     * @return string
     */
    public function model()
    {
        return Application::class;
    }

    /**
     * @return string
     */
    public function selectApplicationPostSql(): string
    {
        $supervisor = ApplicationType::SUPERVISOR;
        $enumerator = ApplicationType::ENUMERATOR;
        $both       = ApplicationType::ENUMERATOR_SUPERVISOR;

        return "case
                   when for_supervisor is true and for_enumerator is true then '{$both}'
                   when for_supervisor is true and for_enumerator is false then '{$supervisor}'
                   when for_supervisor is false and for_enumerator is true then '{$enumerator}'
                   else 'na' end as application_post";
    }
}
