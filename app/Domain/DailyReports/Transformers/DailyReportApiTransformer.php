<?php

namespace App\Domain\DailyReports\Transformers;

use App\Domain\DailyReports\Models\DailyReport;
use App\Infrastructure\Support\Helper;
use League\Fractal\TransformerAbstract;

/**
 * class DailyReportApiTransformer
 */
class DailyReportApiTransformer extends TransformerAbstract
{
    /**
     * @param DailyReport $dailyReport
     *
     * @return array
     */
    public function transform(DailyReport $dailyReport): array
    {
        return [
            'id'              => $dailyReport->id,
            'total_surveyed'  => $dailyReport->total_surveyed,
            'created_by'      => $dailyReport->created_by,
            'enumerator_id'   => $dailyReport->enumerator->id ?? '',
            'enumerator_name' => $dailyReport->enumerator->name ?? '',
            'report_date'     => Helper::dateResponse($dailyReport->report_date),
        ];
    }
}
