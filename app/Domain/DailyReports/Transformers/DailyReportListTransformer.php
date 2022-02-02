<?php

namespace App\Domain\DailyReports\Transformers;

use App\Domain\DailyReports\Models\DailyReport;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

/**
 * Class DailyReportListTransformer
 *
 * @package App\Domain\DailyReports\Transformers
 */
class DailyReportListTransformer extends TransformerAbstract
{
    public function transform(DailyReport $dailyReport): array
    {
        return [
            'id'             => $dailyReport->id,
            'total_surveyed' => $dailyReport->total_surveyed,
            'created_by'     => $dailyReport->created_by,
            'created_at'     => Helper::dateResponse($dailyReport->created_at),
            'report_date'    => Helper::dateResponse($dailyReport->report_date),
            'district'       => optional($dailyReport->district)->title_en,
            'census_office'  => optional($dailyReport->censusOffice)->office_name,
            'supervisor'     => optional($dailyReport->supervisor)->display_name,
            'enumerator'     => optional($dailyReport->enumerator)->name,
            'enumerator_id'  => optional($dailyReport->enumerator)->id,
        ];
    }
}
