<?php

namespace App\Domain\HouseDailyReports\Transformers;

use App\Domain\HouseDailyReports\Models\HouseDailyReport;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

/**
 * class HouseDailyReportApiTransformer
 */
class HouseDailyReportApiTransformer extends TransformerAbstract
{
    /**
     * @param HouseDailyReport $dailyReport
     *
     * @return array
     */
    public function transform(HouseDailyReport $dailyReport): array
    {
        return [
            'id'                           => $dailyReport->id,
            'total_surveyed'               => $dailyReport->total_surveyed,
            'number_of_houses_in_census'   => $dailyReport->number_of_houses_in_census,
            'number_of_families_in_census' => $dailyReport->number_of_families_in_census,
            'created_by'                   => $dailyReport->created_by,
            'report_date'                  => Helper::dateResponse($dailyReport->report_date),
        ];
    }
}
