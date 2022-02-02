<?php

namespace App\Domain\HouseDailyReports\Transformers;

use App\Domain\HouseDailyReports\Models\HouseDailyReport;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

/**
 * Class HouseDailyReportListTransformer
 *
 * @package App\Domain\HouseDailyReports\Transformers
 */
class HouseDailyReportListTransformer extends TransformerAbstract
{
    public function transform(HouseDailyReport $dailyReport): array
    {
        return [
            'id'                           => $dailyReport->id,
            'total_surveyed'               => $dailyReport->total_surveyed,
            'number_of_houses_in_census'   => $dailyReport->number_of_houses_in_census,
            'number_of_families_in_census' => $dailyReport->number_of_families_in_census,
            'created_by'                   => $dailyReport->created_by,
            'created_at'                   => Helper::dateResponse($dailyReport->created_at),
            'report_date'                  => Helper::dateResponse($dailyReport->report_date),
            'district'                     => optional($dailyReport->district)->title_en,
            'census_office'                => optional($dailyReport->censusOffice)->office_name,
            'supervisor'                   => optional($dailyReport->supervisor)->display_name,
        ];
    }
}
